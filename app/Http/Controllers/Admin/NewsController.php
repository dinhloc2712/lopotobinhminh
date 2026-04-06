<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $perPage = $request->input('per_page', 20);
        $search = $request->input('search');

        $query = News::with('creator');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }
        
        // User with view_news permission can see all news
        if ($user->can('view_news')) {
            $news = $query->latest()->paginate($perPage)->withQueryString();
        } else {
            // Regular user sees only news targeted to them
            $news = $query->where(function($qItem) use ($user) {
                $qItem->where('recipient_type', 'all')
                      ->orWhere(function($q) use ($user) {
                          $q->where('recipient_type', 'role')
                            ->whereJsonContains('recipient_ids', (string) $user->role_id);
                      })
                      ->orWhere(function($q) use ($user) {
                          $q->where('recipient_type', 'user')
                            ->whereJsonContains('recipient_ids', (string) $user->id);
                      });
            })->latest()->paginate($perPage)->withQueryString();
        }

        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check permission to create news
        if (!auth()->user()->can('create_news')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::all();
        $users = User::where('is_active', true)->get();
        return view('admin.news.create', compact('roles', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create_news')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,role,user',
            'recipient_ids' => 'nullable|array',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240' // max 10MB
        ]);

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $attachmentPaths[] = $file->storeAs('news_attachments', $filename, 'public');
            }
        }

        News::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'recipient_type' => $validated['recipient_type'],
            // format IDs to string in json to easily search using integer strings
            'recipient_ids' => $validated['recipient_type'] === 'all' ? null : array_map('strval', $request->input('recipient_ids', [])),
            'attachment' => !empty($attachmentPaths) ? $attachmentPaths : null,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Tạo thông báo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        $user = auth()->user();

        // Check if user is allowed to view
        $isAllowed = false;
        if ($user->can('view_news') || $news->created_by === $user->id) {
            $isAllowed = true;
        } else if ($news->recipient_type === 'all') {
            $isAllowed = true;
        } else if ($news->recipient_type === 'role' && in_array((string)$user->role_id, $news->recipient_ids ?? [])) {
            $isAllowed = true;
        } else if ($news->recipient_type === 'user' && in_array((string)$user->id, $news->recipient_ids ?? [])) {
            $isAllowed = true;
        }

        if (!$isAllowed) {
            abort(403, 'Unauthorized action.');
        }

        // Mark as read
        if (!$news->isReadBy($user)) {
            $news->reads()->create(['user_id' => $user->id]);
        }

        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        if (!auth()->user()->can('update_news')) {
            abort(403);
        }

        $roles = Role::all();
        $users = User::where('is_active', true)->get();
        return view('admin.news.edit', compact('news', 'roles', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        if (!auth()->user()->can('update_news')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,role,user',
            'recipient_ids' => 'nullable|array',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240'
        ]);

        $dataToUpdate = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'recipient_type' => $validated['recipient_type'],
            'recipient_ids' => $validated['recipient_type'] === 'all' ? null : array_map('strval', $request->input('recipient_ids', [])),
        ];

        $existingAttachments = $news->attachment ?? [];

        if ($request->has('remove_attachments')) {
            $removeKeys = $request->input('remove_attachments');
            foreach ($removeKeys as $key => $val) {
                if (isset($existingAttachments[$key]) && Storage::disk('public')->exists($existingAttachments[$key])) {
                    Storage::disk('public')->delete($existingAttachments[$key]);
                }
                unset($existingAttachments[$key]);
            }
            $existingAttachments = array_values($existingAttachments);
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $existingAttachments[] = $file->storeAs('news_attachments', $filename, 'public');
            }
        }
        
        $dataToUpdate['attachment'] = empty($existingAttachments) ? null : $existingAttachments;

        $news->update($dataToUpdate);

        return redirect()->route('admin.news.index')->with('success', 'Cập nhật thông báo thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        if (!auth()->user()->can('delete_news')) {
            abort(403);
        }

        if (!empty($news->attachment)) {
            foreach ($news->attachment as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Xóa thông báo thành công.');
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(News $news, Request $request)
    {
        $user = auth()->user();

        // Check view permission before downloading
        $isAllowed = false;
        if ($user->can('view_news') || $news->created_by === $user->id) {
            $isAllowed = true;
        } else if ($news->recipient_type === 'all') {
            $isAllowed = true;
        } else if ($news->recipient_type === 'role' && in_array((string)$user->role_id, $news->recipient_ids ?? [])) {
            $isAllowed = true;
        } else if ($news->recipient_type === 'user' && in_array((string)$user->id, $news->recipient_ids ?? [])) {
            $isAllowed = true;
        }

        if (!$isAllowed) {
            abort(403, 'Unauthorized action.');
        }

        $index = $request->query('index', 0);
        $attachments = $news->attachment ?? [];

        if (!isset($attachments[$index]) || !Storage::disk('public')->exists($attachments[$index])) {
            abort(404, 'Không tìm thấy file.');
        }

        return Storage::disk('public')->download($attachments[$index]);
    }
}
