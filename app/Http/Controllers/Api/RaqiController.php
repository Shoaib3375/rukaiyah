<?php

namespace App\Http\Controllers\Api;

use App\Models\RaqiProfile;
use App\Enums\RaqiStatus;
use Illuminate\Http\JsonResponse;

class RaqiController extends ApiController
{
    public function index(): JsonResponse
    {
        $raqis = RaqiProfile::with('user')
            ->where('is_approved', true)
            ->where('status', RaqiStatus::Active)
            ->get();

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
