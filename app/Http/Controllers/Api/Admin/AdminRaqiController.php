<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\RaqiProfile;
use Illuminate\Http\JsonResponse;

class AdminRaqiController extends ApiController
{
    public function pending(): JsonResponse
    {
        $raqis = RaqiProfile::where('is_approved', false)->with('user')->get();
        return $this->success($raqis);
    }

    public function approve(RaqiProfile $raqi): JsonResponse
    {
        $raqi->update([
            'is_approved' => true,
            'approved_at' => now(),
            'status' => 'active',
        ]);
        return $this->success(null, 'Raqi approved.');
    }

    public function suspend(RaqiProfile $raqi): JsonResponse
    {
        $raqi->update(['status' => 'suspended']);
        return $this->success(null, 'Raqi suspended.');
    }
}
