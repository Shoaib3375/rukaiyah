<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lead_raqi_id' => 'required|exists:raqi_profiles,id',
            'session_type' => 'required|in:video,chat,phone,in_person',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'integer|min:15|max:180',
            'patient_notes' => 'nullable|string',
        ];
    }
}
