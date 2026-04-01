<?php

namespace App\Http\Controllers\Api;

use App\Enums\AppointmentStatus;
use App\Http\Requests\RaqiProfile\UpdatePasswordRequest;
use App\Http\Requests\RaqiProfile\UpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RaqiProfileController extends ApiController
{
    public function show(): JsonResponse
    {
        $user = auth('api')->user();

        return $this->success($this->profilePayload($user));
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        $user = auth('api')->user();
        $profile = $user->raqiProfile;

        $profile->update($request->only(['bio', 'specialization', 'languages']));
        $user->update($request->only(['full_name', 'email', 'phone']));

        $user->refresh();

        return $this->success($this->profilePayload($user), 'Profile updated successfully.');
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = auth('api')->user();
        $user->update([
            'password' => Hash::make($request->validated()['password']),
        ]);

        return $this->success(null, 'Password updated successfully.');
    }

    private function profilePayload(User $user): array
    {
        $profile = $user->raqiProfile;
        $data = $profile->toArray();
        $data['full_name'] = $user->full_name;
        $data['email'] = $user->email;
        $data['phone'] = $user->phone;
        $data['is_verified'] = (bool) $user->is_verified;
        $data['email_verified_at'] = $user->email_verified_at;

        $data['completed_sessions_count'] = $profile->appointments()
            ->where('status', AppointmentStatus::Completed)
            ->count();

        $data['upcoming_sessions_count'] = $profile->appointments()
            ->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Confirmed])
            ->where('scheduled_at', '>=', now())
            ->count();

        return $data;
    }
}
