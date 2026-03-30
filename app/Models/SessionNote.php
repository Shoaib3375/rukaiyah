<?php

namespace App\Models;

use App\Enums\NoteType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionNote extends Model
{
    use HasUuids;

    protected $fillable = [
        'appointment_id',
        'raqi_id',
        'content',
        'note_type',
    ];

    protected $casts = [
        'note_type' => NoteType::class,
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
