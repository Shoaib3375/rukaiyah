<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

class NotificationController extends ApiController
{
    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $limit = min($request->query('limit', 20), 100);
        $notifications = auth('api')->user()->notifications()
            ->select(['id', 'user_id', 'appointment_id', 'title', 'body', 'channel', 'is_read', 'sent_at', 'created_at'])
            ->latest()
            ->paginate($limit);
        return $this->success($notifications);
    }

    public function markRead($notification): JsonResponse
    {
        $notification = auth('api')->user()->notifications()->findOrFail($notification);
        $notification->update(['is_read' => true]);
        return $this->success(null, 'Notification marked as read.');
    }
}
