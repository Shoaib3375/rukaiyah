<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUp extends Model
{
    use HasUuids;

    protected $fillable = [
        'appointment_id',
        'raqi_id',
        'recommendation',
        'follow_up_date',
        'is_completed',
    ];

    protected $casts = [
        'follow_up_date' => 'datetime',
        'is_completed' => 'boolean',
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
