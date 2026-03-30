<?php

namespace App\Http\Controllers\Api;

use App\Models\RaqiProfile;
use App\Enums\RaqiStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RaqiController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $limit = min($request->query('limit', 12), 100);
        $raqis = RaqiProfile::select(['id', 'user_id', 'specialization', 'bio', 'is_approved', 'status', 'rating', 'total_reviews'])
            ->with('user:id,full_name,email,phone')
            ->where('is_approved', true)
            ->where('status', RaqiStatus::Active)
            ->latest('rating')
            ->paginate($limit);

        return $this->success($raqis);
    }

    public function show(RaqiProfile $raqi): JsonResponse
    {
        if (!$raqi->is_approved || $raqi->status !== RaqiStatus::Active) {
            return $this->error('Raqi not found or not active.', 404);
        }

        return $this->success($raqi->load(['user', 'availabilities', 'reviews.patient']));
    }
}
