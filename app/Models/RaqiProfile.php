<?php

namespace App\Models;

use App\Enums\RaqiStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RaqiProfile extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'specialization',
        'languages',
        'status',
        'is_approved',
        'approved_at',
        'rating',
        'total_reviews',
    ];

    protected $casts = [
        'status' => RaqiStatus::class,
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'rating' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class, 'raqi_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'lead_raqi_id');
    }

    public function sessionParticipations(): HasMany
    {
        return $this->hasMany(SessionParticipant::class, 'raqi_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'raqi_id');
    }

    public function recalculateRating(): void
    {
        $this->update([
            'rating'        => round($this->reviews()->avg('rating') ?? 0, 2),
            'total_reviews' => $this->reviews()->count(),
        ]);
    }
}
