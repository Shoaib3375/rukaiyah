<?php

namespace App\Http\Controllers\Api;

use App\Enums\AppointmentStatus;
use App\Http\Requests\PatientProfile\UpdatePasswordRequest;
use App\Http\Requests\PatientProfile\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class PatientProfileController extends ApiController
{
    public function show(): JsonResponse
    {
        $user = auth('api')->user();
        $profile = $user->patientProfile;

        $completedSessions = $user->appointments()
            ->where('status', AppointmentStatus::Completed)
            ->count();

        $data = $profile->toArray();
        $data['full_name'] = $user->full_name;
        $data['email'] = $user->email;
        $data['phone'] = $user->phone;
        $data['is_verified'] = (bool) $user->is_verified;
        $data['email_verified_at'] = $user->email_verified_at;
        $data['completed_sessions_count'] = $completedSessions;

        return $this->success($data);
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        $user = auth('api')->user();
        $profile = $user->patientProfile;

        $profile->update($request->only([
            'address', 'emergency_contact', 'medical_notes',
            'display_name', 'dob', 'gender',
            'emergency_contact_relationship', 'emergency_contact_phone', 'emergency_contact_email',
            'preferred_session_type', 'preferred_language', 'raqi_gender_preference', 'timezone',
            'notification_email', 'notification_sms', 'notification_push', 'notification_reminders',
            'primary_ailment', 'secondary_concerns', 'symptom_duration', 'symptom_intensity',
            'symptom_description', 'symptom_triggers', 'hallucinations', 'medical_checkup',
            'previous_ruqyah', 'current_medications', 'madhhab', 'previous_ruqyah_notes',
            'additional_notes', 'consent_ruqyah_shariah', 'consent_medical_disclaimer', 'consent_data_storage'
        ]));
        $user->update($request->only(['full_name', 'email', 'phone']));

        $data = $profile->toArray();
        $data['full_name'] = $user->full_name;
        $data['email']     = $user->email;
        $data['phone']     = $user->phone;

        return $this->success($data, 'Profile updated successfully.');
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = auth('api')->user();
        $user->update([
            'password' => Hash::make($request->validated()['password']),
        ]);

        return $this->success(null, 'Password updated successfully.');
    }

    public function sessionLogs(): JsonResponse
    {
        $user = auth('api')->user();

        $appointments = $user->appointments()
            ->with([
                'leadRaqi.user:id,full_name',
                'notes' => fn ($q) => $q->orderBy('created_at'),
                'notes.raqi.user:id,full_name',
            ])
            ->whereHas('notes')
            ->latest('scheduled_at')
            ->get();

        return $this->success($appointments);
    }
}
