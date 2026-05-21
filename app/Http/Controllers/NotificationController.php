<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Fetch recent notifications for authenticated user.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($n) => [
                'id' => $n->id,
                'icon' => $n->data['icon'] ?? '🔔',
                'title' => $n->data['title'] ?? 'Notifikasi',
                'message' => $n->data['message'] ?? '',
                'type' => $n->data['type'] ?? 'info',
                'booking_code' => $n->data['booking_code'] ?? null,
                'read' => $n->read_at !== null,
                'time' => $n->created_at->diffForHumans(),
                'created_at' => $n->created_at->toIso8601String(),
            ]);

        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(string $id): JsonResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}
