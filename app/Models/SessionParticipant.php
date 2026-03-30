<?php

namespace App\Models;

use App\Enums\InviteStatus;
use App\Enums\ParticipantRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionParticipant extends Model
{
    use HasUuids;

    protected $fillable = [
        'appointment_id',
        'raqi_id',
        'role',
        'invite_status',
        'invited_at',
        'joined_at',
    ];

    protected $casts = [
        'role' => ParticipantRole::class,
        'invite_status' => InviteStatus::class,
        'invited_at' => 'datetime',
        'joined_at' => 'datetime',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function raqi(): BelongsTo
    {
        return $this->belongsTo(RaqiProfile::class, 'raqi_id');
    }
}
