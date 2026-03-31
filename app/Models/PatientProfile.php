<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientProfile extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'emergency_contact',
        'medical_notes',
        'display_name',
        'dob',
        'gender',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'emergency_contact_email',
        'preferred_session_type',
        'preferred_language',
        'raqi_gender_preference',
        'timezone',
        'notification_email',
        'notification_sms',
        'notification_push',
        'notification_reminders',
        'primary_ailment',
        'secondary_concerns',
        'symptom_duration',
        'symptom_intensity',
        'symptom_description',
        'symptom_triggers',
        'hallucinations',
        'medical_checkup',
        'previous_ruqyah',
        'current_medications',
        'madhhab',
        'previous_ruqyah_notes',
        'additional_notes',
        'consent_ruqyah_shariah',
        'consent_medical_disclaimer',
        'consent_data_storage'
    ];

    protected $casts = [
        'secondary_concerns' => 'array',
        'symptom_triggers' => 'array',
        'notification_email' => 'boolean',
        'notification_sms' => 'boolean',
        'notification_push' => 'boolean',
        'notification_reminders' => 'boolean',
        'previous_ruqyah' => 'boolean',
        'consent_ruqyah_shariah' => 'boolean',
        'consent_medical_disclaimer' => 'boolean',
        'consent_data_storage' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
