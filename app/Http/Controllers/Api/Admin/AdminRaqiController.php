<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Enums\RaqiStatus;
use App\Models\RaqiProfile;
use Illuminate\Http\JsonResponse;

class AdminRaqiController extends ApiController
{
    public function pending(): JsonResponse
    {
        $raqis = RaqiProfile::select(['id', 'user_id', 'specialization', 'bio', 'rating', 'total_reviews', 'is_approved', 'status', 'created_at'])
            ->where('is_approved', false)
            ->with('user:id,full_name,email,phone')
            ->latest('created_at')
            ->paginate(min(request()->input('limit', 20), 100));

        return $this->success($raqis);
    }

    public function approved(): JsonResponse
    {
        $raqis = RaqiProfile::select(['id', 'user_id', 'specialization', 'bio', 'rating', 'total_reviews', 'is_approved', 'status', 'created_at'])
            ->where('is_approved', true)
            ->with('user:id,full_name,email,phone')
            ->latest('created_at')
            ->paginate(min(request()->input('limit', 20), 100));

        return $this->success($raqis);
    }

    public function approve(RaqiProfile $raqi): JsonResponse
    {
        $raqi->update([
            'is_approved' => true,
            'approved_at' => now(),
            'status'      => RaqiStatus::Active,
        ]);

        // Bust stats cache so dashboard reflects immediately
        cache()->forget('admin.stats');

        return $this->success(null, 'Raqi approved.');
    }

    public function suspend(RaqiProfile $raqi): JsonResponse
    {
        $raqi->update(['status' => RaqiStatus::Suspended]);

        cache()->forget('admin.stats');

        return $this->success(null, 'Raqi suspended.');
    }
}
