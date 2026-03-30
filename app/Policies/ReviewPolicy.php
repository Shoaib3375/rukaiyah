<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function create(User $user): bool
    {
        return $user->role->value === 'patient';
    }

    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->patient_id;
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->patient_id || $user->role->value === 'admin';
    }
}
