<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RaqiProfile\UpdateRequest;
use Illuminate\Http\JsonResponse;

class RaqiProfileController extends ApiController
{
    public function show(): JsonResponse
    {
        $user = auth('api')->user();
        $profile = $user->raqiProfile;
        
        $data = $profile->toArray();
        $data['full_name'] = $user->full_name;
        $data['email']     = $user->email;
        $data['phone']     = $user->phone;

        return $this->success($data);
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        $user = auth('api')->user();
        $profile = $user->raqiProfile;

        $profile->update($request->only(['bio', 'specialization', 'languages']));
        $user->update($request->only(['full_name', 'email', 'phone']));

        $data = $profile->toArray();
        $data['full_name'] = $user->full_name;
        $data['email']     = $user->email;
        $data['phone']     = $user->phone;

        return $this->success($data, 'Profile updated successfully.');
    }
}
