<?php

namespace Database\Factories;

use App\Enums\RaqiStatus;
use App\Models\RaqiProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RaqiProfile>
 */
class RaqiProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specializations = [
            'Ruqyah (Quranic Healing)',
            'Islamic Counseling',
            'Spiritual Guidance',
            'Islamic Psychology',
            'Trauma Healing',
            'Relationship Counseling',
            'Quran Recitation Therapy',
            'Islamic Life Coaching',
        ];

        $languages = ['English', 'Arabic', 'Urdu', 'French', 'Turkish', 'Indonesian'];

        return [
            'user_id' => User::factory(),
            'bio' => fake()->paragraph(),
            'specialization' => fake()->randomElement($specializations),
            'languages' => json_encode(fake()->randomElements($languages, fake()->numberBetween(1, 3))),
            'status' => RaqiStatus::Active,
            'is_approved' => true,
            'approved_at' => now()->subDays(fake()->numberBetween(1, 90)),
            'rating' => fake()->randomFloat(2, 3.5, 5.0),
            'total_reviews' => fake()->numberBetween(0, 50),
        ];
    }

    /**
     * Indicate that the Raqi is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
            'approved_at' => null,
            'status' => RaqiStatus::Pending,
        ]);
    }

    /**
     * Indicate that the Raqi is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RaqiStatus::Suspended,
        ]);
    }
}
