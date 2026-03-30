<?php

namespace Database\Factories;

use App\Models\PatientProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PatientProfile>
 */
class PatientProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'address' => fake()->address(),
            'emergency_contact' => fake()->name() . ' - ' . fake()->phoneNumber(),
            'medical_notes' => fake()->optional(0.5)->paragraph(),
        ];
    }
}
