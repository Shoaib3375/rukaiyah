<?php

namespace App\Http\Requests\PatientProfile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = auth('api')->user();
        return [
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . ($user ? $user->id : 'NULL') . ',id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'medical_notes' => 'nullable|string',

            'display_name' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'emergency_contact_relationship' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'emergency_contact_email' => 'nullable|email',

            'preferred_session_type' => 'nullable|string',
            'preferred_language' => 'nullable|string',
            'raqi_gender_preference' => 'nullable|string',
            'timezone' => 'nullable|string',
            'notification_email' => 'nullable|boolean',
            'notification_sms' => 'nullable|boolean',
            'notification_push' => 'nullable|boolean',
            'notification_reminders' => 'nullable|boolean',

            'primary_ailment' => 'nullable|string',
            'secondary_concerns' => 'nullable|array',
            'symptom_duration' => 'nullable|string',
            'symptom_intensity' => 'nullable|integer|min:1|max:10',
            'symptom_description' => 'nullable|string',
            'symptom_triggers' => 'nullable|array',
            'hallucinations' => 'nullable|string',

            'medical_checkup' => 'nullable|string',
            'previous_ruqyah' => 'nullable|boolean',
            'current_medications' => 'nullable|string',
            'madhhab' => 'nullable|string',
            'previous_ruqyah_notes' => 'nullable|string',
            'additional_notes' => 'nullable|string',

            'consent_ruqyah_shariah' => 'nullable|boolean',
            'consent_medical_disclaimer' => 'nullable|boolean',
            'consent_data_storage' => 'nullable|boolean',
        ];
    }
}
