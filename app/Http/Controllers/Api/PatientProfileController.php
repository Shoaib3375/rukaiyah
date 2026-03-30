<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PatientProfile\UpdateRequest;
use Illuminate\Http\JsonResponse;

class PatientProfileController extends ApiController
{
    public function show(): JsonResponse
    {
        return $this->success(auth('api')->user()->patientProfile);
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        $profile = auth('api')->user()->patientProfile;
        $profile->update($request->validated());
        return $this->success($profile, 'Profile updated successfully.');
    }
}
