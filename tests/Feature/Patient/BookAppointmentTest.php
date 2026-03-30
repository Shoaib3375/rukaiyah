<?php

use App\Models\User;
use App\Models\RaqiProfile;
use App\Enums\UserRole;
use App\Enums\RaqiStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a patient can book an appointment', function () {
    $patient = User::factory()->create(['role' => UserRole::Patient]);
    $raqiUser = User::factory()->create(['role' => UserRole::Raqi]);
    $raqiProfile = RaqiProfile::create([
        'user_id' => $raqiUser->id,
        'is_approved' => true,
        'status' => RaqiStatus::Active,
    ]);

    $response = $this->actingAs($patient, 'api')
        ->postJson('/api/v1/patient/appointments', [
            'lead_raqi_id' => $raqiProfile->id,
            'session_type' => 'video',
            'scheduled_at' => now()->addDays(1)->toIso8601String(),
            'duration_minutes' => 60,
            'patient_notes' => 'Test notes',
        ]);

    $response->dump();
    $response->assertStatus(201);
    $this->assertDatabaseHas('appointments', [
        'patient_id' => $patient->id,
        'lead_raqi_id' => $raqiProfile->id,
    ]);
});
