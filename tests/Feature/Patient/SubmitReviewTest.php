<?php

use App\Enums\AppointmentStatus;
use App\Enums\RaqiStatus;
use App\Enums\SessionType;
use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\RaqiProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a patient can submit a review after a completed session', function () {
    $patient = User::factory()->create(['role' => UserRole::Patient]);
    $raqiUser = User::factory()->create(['role' => UserRole::Raqi]);
    $raqiProfile = RaqiProfile::create([
        'user_id' => $raqiUser->id,
        'is_approved' => true,
        'status' => RaqiStatus::Active,
    ]);

    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'lead_raqi_id' => $raqiProfile->id,
        'session_type' => SessionType::Video,
        'status' => AppointmentStatus::Completed,
        'scheduled_at' => now()->subDay(),
        'duration_minutes' => 60,
    ]);

    $response = $this->actingAs($patient, 'api')
        ->postJson('/api/v1/patient/reviews', [
            'appointment_id' => $appointment->id,
            'rating' => 5,
            'comment' => 'Excellent session.',
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('reviews', [
        'appointment_id' => $appointment->id,
        'patient_id' => $patient->id,
        'raqi_id' => $raqiProfile->id,
        'rating' => 5,
    ]);

    $raqiProfile->refresh();
    expect((float) $raqiProfile->rating)->toBe(5.0);
    expect($raqiProfile->total_reviews)->toBe(1);
});

test('a patient cannot review a session that is not completed', function () {
    $patient = User::factory()->create(['role' => UserRole::Patient]);
    $raqiUser = User::factory()->create(['role' => UserRole::Raqi]);
    $raqiProfile = RaqiProfile::create([
        'user_id' => $raqiUser->id,
        'is_approved' => true,
        'status' => RaqiStatus::Active,
    ]);

    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'lead_raqi_id' => $raqiProfile->id,
        'session_type' => SessionType::Video,
        'status' => AppointmentStatus::Confirmed,
        'scheduled_at' => now()->addDay(),
        'duration_minutes' => 60,
    ]);

    $response = $this->actingAs($patient, 'api')
        ->postJson('/api/v1/patient/reviews', [
            'appointment_id' => $appointment->id,
            'rating' => 4,
        ]);

    $response->assertStatus(403);
});

test('a patient cannot submit two reviews for the same appointment', function () {
    $patient = User::factory()->create(['role' => UserRole::Patient]);
    $raqiUser = User::factory()->create(['role' => UserRole::Raqi]);
    $raqiProfile = RaqiProfile::create([
        'user_id' => $raqiUser->id,
        'is_approved' => true,
        'status' => RaqiStatus::Active,
    ]);

    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'lead_raqi_id' => $raqiProfile->id,
        'session_type' => SessionType::Video,
        'status' => AppointmentStatus::Completed,
        'scheduled_at' => now()->subDay(),
        'duration_minutes' => 60,
    ]);

    $this->actingAs($patient, 'api')
        ->postJson('/api/v1/patient/reviews', [
            'appointment_id' => $appointment->id,
            'rating' => 5,
        ])
        ->assertStatus(201);

    $this->actingAs($patient, 'api')
        ->postJson('/api/v1/patient/reviews', [
            'appointment_id' => $appointment->id,
            'rating' => 3,
        ])
        ->assertStatus(403);
});
