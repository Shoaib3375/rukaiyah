<?php

namespace App\Services;

use App\Models\User;
use App\Models\PatientProfile;
use App\Models\RaqiProfile;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthService
{
    public static function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            if ($data['role'] === UserRole::Patient->value) {
                PatientProfile::create(['user_id' => $user->id]);
            } elseif ($data['role'] === UserRole::Raqi->value) {
                RaqiProfile::create(['user_id' => $user->id]);
            }

            return $user;
        });
    }
}
