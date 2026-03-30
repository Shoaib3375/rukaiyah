<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

class NotificationController extends ApiController
{
    public function index(): JsonResponse
    {
        $notifications = auth('api')->user()->notifications()->latest()->get();
        return $this->success($notifications);
    }

    public function markRead($notification): JsonResponse
    {
        $notification = auth('api')->user()->notifications()->findOrFail($notification);
        $notification->update(['is_read' => true]);
        return $this->success(null, 'Notification marked as read.');
    }
}
