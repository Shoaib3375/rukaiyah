<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    use HasUuids;

    protected $fillable = [
        'raqi_id',
        'day_of_week',
        'slot_start',
        'slot_end',
        'is_blocked',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
    ];

    public function raqi(): BelongsTo
    {
        return $this->belongsTo(RaqiProfile::class, 'raqi_id');
    }
}
