<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $this->authorize('view_chat');

        return view('admin.chat.index');
    }

    public function fetchUsers()
    {
        $users = User::where('id', '!=', Auth::id())->get(['id', 'name', 'email']);
        return response()->json($users);
    }

    public function fetchConversations()
    {
        $user = Auth::user();

        $conversations = $user->conversations()
            ->with(['users' => function ($q) {
                $q->select('users.id', 'name', 'email');
            }])
            ->with(['lastMessage.user:id,name'])
            ->orderByDesc('conversations.updated_at')
            ->get();

        return response()->json($conversations);
    }

    public function fetchMessages(Conversation $conversation)
    {
        // Ensure user is part of conversation
        if (!$conversation->users->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()->with('user:id,name')->orderBy('created_at', 'asc')->get();
        
        // Update read_at
        $conversation->users()->updateExistingPivot(Auth::id(), ['read_at' => now()]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        if (!$conversation->users->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'body' => 'nullable|string',
            'attachments.*' => 'nullable|file',
        ]);

        if (!$request->body && !$request->hasFile('attachments')) {
            return response()->json(['error' => 'Message is empty'], 422);
        }

        $message = new Message();
        $message->conversation_id = $conversation->id;
        $message->user_id = Auth::id();
        $message->body = $request->body;

        if ($request->hasFile('attachments')) {
            $paths = [];
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('chat_attachments', 'public');
            }
            $message->attachment = $paths;
        }

        $message->save();
        $message->load('user:id,name');

        // Update conversation timestamp for correct ordering
        $conversation->touch();

        // Broadcast real-time event to all conversation participants
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function startConversation(Request $request)
    {
        $this->authorize('create_chat');
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $targetUserId = $request->user_id;

        // Check if there is already a 1-on-1 conversation
        $conversation = Conversation::where('is_group', false)
            ->whereHas('users', function ($q) {
                $q->where('users.id', Auth::id());
            })
            ->whereHas('users', function ($q) use ($targetUserId) {
                $q->where('users.id', $targetUserId);
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'is_group' => false,
            ]);
            $conversation->users()->attach([Auth::id(), $targetUserId]);
        }

        return response()->json($conversation->load(['users' => function ($q) {
            $q->select('users.id', 'name', 'email');
        }]));
    }
    public function startGroupChat(Request $request)
    {
        $this->authorize('create_chat');

        $request->validate([
            'name' => 'required|string|max:100',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $request->user_ids;
        $userIds[] = Auth::id(); // Add creator
        $userIds = array_unique($userIds);

        if (count($userIds) < 2) {
            return response()->json(['error' => 'Not enough users'], 422);
        }

        $conversation = Conversation::create([
            'is_group' => true,
            'name' => $request->name,
        ]);

        $conversation->users()->attach($userIds);

        return response()->json($conversation->load(['users' => function ($q) {
            $q->select('users.id', 'name', 'email');
        }]));
    }
    public function addMember(Request $request, Conversation $conversation)
    {

        $this->authorize('edit_chat');
        if (!$conversation->is_group) {
            return response()->json(['error' => 'Not a group chat'], 400);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Prevent adding current user or unauthorized users doing it?
        // Assuming any member can add, but let's check membership
        if (!$conversation->users->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $targetUserId = $request->user_id;

        if (!$conversation->users->contains($targetUserId)) {
            $conversation->users()->attach($targetUserId);
        }

        return response()->json($conversation->load(['users' => function ($q) {
            $q->select('users.id', 'name', 'email');
        }]));
    }

    public function removeMember(Request $request, Conversation $conversation)
    {
        $this->authorize('edit_chat');

        if (!$conversation->is_group) {
            return response()->json(['error' => 'Not a group chat'], 400);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        if (!$conversation->users->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $targetUserId = $request->user_id;

        // Optionally prevent removing the last member, or checking permissions
        if ($conversation->users->contains($targetUserId)) {
            $conversation->users()->detach($targetUserId);
        }

        return response()->json($conversation->load(['users' => function ($q) {
            $q->select('users.id', 'name', 'email');
        }]));
    }

    public function updateGroupChatName(Request $request, Conversation $conversation)
    {
        $this->authorize('edit_chat');

        if (!$conversation->is_group) {
            return response()->json(['error' => 'Not a group chat'], 400);
        }

        if (!$conversation->users->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $conversation->name = $request->name;
        $conversation->save();

        return response()->json($conversation->load(['users' => function ($q) {
            $q->select('users.id', 'name', 'email');
        }]));
    }

    public function destroyGroupChat(Conversation $conversation)
    {
        $this->authorize('delete_chat');
        if (!$conversation->is_group) {
            return response()->json(['error' => 'Not a group chat'], 400);
        }

        if (!$conversation->users->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete all messages first to prevent constraint violations
        $conversation->messages()->delete();
        
        // Detach all users
        $conversation->users()->detach();

        // Delete the conversation
        $conversation->delete();

        return response()->json(['success' => true]);
    }
}
