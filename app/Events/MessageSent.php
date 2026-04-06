<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Broadcast on the private channel for the conversation.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];
    }

    /**
     * The data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id'              => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'user_id'         => $this->message->user_id,
            'body'            => $this->message->body,
            'attachment'      => $this->message->attachment,
            'created_at'      => $this->message->created_at->toISOString(),
            'user'            => [
                'id'   => $this->message->user->id,
                'name' => $this->message->user->name,
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
