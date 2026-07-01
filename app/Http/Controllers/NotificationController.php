<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = min((int) $request->query('limit', 10), 50);
        $role = $request->user()->role;

        $notifications = Notification::where('target_role', $role)
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (Notification $notification) => $this->formatNotification($notification));

        $unreadCount = Notification::where('target_role', $role)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $unreadCount = Notification::where('target_role', $request->user()->role)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request, Notification $notification): JsonResponse
    {
        abort_if($notification->target_role !== $request->user()->role, 403);

        $notification->update(['is_read' => true]);

        return response()->json([
            'notification' => $this->formatNotification($notification->fresh()),
        ]);
    }

    public function readAll(Request $request): JsonResponse
    {
        Notification::where('target_role', $request->user()->role)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'message' => 'Semua notifikasi sudah ditandai dibaca.',
        ]);
    }

    private function formatNotification(Notification $notification): array
    {
        return [
            'id' => $notification->id,
            'type' => $notification->type,
            'reference_id' => $notification->reference_id,
            'message' => $notification->message,
            'is_read' => $notification->is_read,
            'created_at' => $notification->created_at?->toIso8601String(),
            'created_at_human' => $notification->created_at?->diffForHumans(),
        ];
    }
}
