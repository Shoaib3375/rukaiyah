<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PatientProfile\UpdateRequest;
use Illuminate\Http\JsonResponse;

class PatientProfileController extends ApiController
{
    public function show(): JsonResponse
    {
        $user = auth('api')->user();
        $profile = $user->patientProfile;
        
        $data = $profile->toArray();
        $data['full_name'] = $user->full_name;
        $data['email']     = $user->email;
        $data['phone']     = $user->phone;

        return $this->success($data);
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        $user = auth('api')->user();
        $profile = $user->patientProfile;

        $profile->update($request->only(['address', 'emergency_contact', 'medical_notes']));
        $user->update($request->only(['full_name', 'email', 'phone']));

        $data = $profile->toArray();
        $data['full_name'] = $user->full_name;
        $data['email']     = $user->email;
        $data['phone']     = $user->phone;

        return $this->success($data, 'Profile updated successfully.');
    }
}
