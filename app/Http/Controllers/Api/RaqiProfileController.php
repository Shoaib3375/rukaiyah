<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RaqiProfile\UpdateRequest;
use Illuminate\Http\JsonResponse;

class RaqiProfileController extends ApiController
{
    public function show(): JsonResponse
    {
        return $this->success(auth('api')->user()->raqiProfile);
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        $profile = auth('api')->user()->raqiProfile;
        $profile->update($request->validated());
        return $this->success($profile, 'Profile updated successfully.');
    }
}
