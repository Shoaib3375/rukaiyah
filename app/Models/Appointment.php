<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use App\Enums\SessionType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'lead_raqi_id',
        'session_type',
        'status',
        'scheduled_at',
        'duration_minutes',
        'patient_notes',
        'raqi_notes',
        'cancel_reason',
    ];

    protected $casts = [
        'session_type' => SessionType::class,
        'status'       => AppointmentStatus::class,
        'scheduled_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function leadRaqi(): BelongsTo
    {
        return $this->belongsTo(RaqiProfile::class, 'lead_raqi_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(SessionParticipant::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(SessionNote::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function followUp(): HasOne
    {
        return $this->hasOne(FollowUp::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function isCancellable(): bool
    {
        return $this->scheduled_at->diffInHours(now()) > 2
            && in_array($this->status, [AppointmentStatus::Pending, AppointmentStatus::Confirmed]);
    }
}
