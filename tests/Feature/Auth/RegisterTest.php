<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a user can register as a patient', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'patient',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => [
                'access_token',
                'token_type',
                'expires_in',
                'user' => [
                    'id',
                    'full_name',
                    'email',
                    'role',
                ],
            ],
            'message',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
        'role' => 'patient',
    ]);

    $this->assertDatabaseHas('patient_profiles', [
        'user_id' => User::where('email', 'john@example.com')->first()->id,
    ]);
});

test('a user cannot register with invalid email', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'full_name' => 'John Doe',
        'email' => 'invalid-email',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'patient',
    ]);

    $response->assertStatus(422);
});
