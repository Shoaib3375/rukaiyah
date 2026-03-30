<?php

namespace App\Http\Requests\Availability;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'day_of_week' => 'required|integer|min:0|max:6',
            'slot_start' => 'required|date_format:H:i',
            'slot_end' => 'required|date_format:H:i|after:slot_start',
            'is_blocked' => 'boolean',
        ];
    }
}
