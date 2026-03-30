<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\PatientProfile;
use App\Models\RaqiProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'full_name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin,
            'is_active' => true,
            'is_verified' => true,
        ]);

        // Create 5 patients with profiles
        User::factory()
            ->count(5)
            ->state(fn () => [
                'role' => UserRole::Patient,
                'is_active' => true,
                'is_verified' => true,
            ])
            ->create()
            ->each(function (User $user) {
                PatientProfile::factory()->create(['user_id' => $user->id]);
            });

        // Create 8 approved Raqis with profiles
        User::factory()
            ->count(8)
            ->state(fn () => [
                'role' => UserRole::Raqi,
                'is_active' => true,
                'is_verified' => true,
            ])
            ->create()
            ->each(function (User $user) {
                RaqiProfile::factory()->create(['user_id' => $user->id]);
            });

        // Create 3 pending Raqis (not yet approved)
        User::factory()
            ->count(3)
            ->state(fn () => [
                'role' => UserRole::Raqi,
                'is_active' => true,
                'is_verified' => false,
            ])
            ->create()
            ->each(function (User $user) {
                RaqiProfile::factory()->pending()->create(['user_id' => $user->id]);
            });
    }
}

