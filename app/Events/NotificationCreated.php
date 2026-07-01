<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public Notification $notification)
    {
        //
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('role.' . $this->notification->target_role);
    }

    public function broadcastAs(): string
    {
        return 'notification.created';
    }

    public function broadcastWith(): array
    {
        return [
            'notification' => [
                'id' => $this->notification->id,
                'target_role' => $this->notification->target_role,
                'type' => $this->notification->type,
                'reference_id' => $this->notification->reference_id,
                'message' => $this->notification->message,
                'is_read' => $this->notification->is_read,
                'created_at' => $this->notification->created_at?->toIso8601String(),
                'created_at_human' => $this->notification->created_at?->diffForHumans(),
            ],
        ];
    }
}
