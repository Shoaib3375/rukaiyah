<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patient_profiles', function (Blueprint $table) {
            $table->string('display_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();

            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_email')->nullable();

            $table->string('preferred_session_type')->nullable();
            $table->string('preferred_language')->nullable();
            $table->string('raqi_gender_preference')->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('notification_email')->default(true);
            $table->boolean('notification_sms')->default(true);
            $table->boolean('notification_push')->default(false);
            $table->boolean('notification_reminders')->default(true);

            $table->string('primary_ailment')->nullable();
            $table->json('secondary_concerns')->nullable();
            $table->string('symptom_duration')->nullable();
            $table->integer('symptom_intensity')->nullable();
            $table->text('symptom_description')->nullable();
            $table->json('symptom_triggers')->nullable();
            $table->string('hallucinations')->nullable();
            
            $table->string('medical_checkup')->nullable();
            $table->boolean('previous_ruqyah')->nullable();
            $table->text('current_medications')->nullable();
            $table->string('madhhab')->nullable();
            $table->text('previous_ruqyah_notes')->nullable();
            $table->text('additional_notes')->nullable();

            $table->boolean('consent_ruqyah_shariah')->default(false);
            $table->boolean('consent_medical_disclaimer')->default(false);
            $table->boolean('consent_data_storage')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'display_name', 'dob', 'gender',
                'emergency_contact_relationship', 'emergency_contact_phone', 'emergency_contact_email',
                'preferred_session_type', 'preferred_language', 'raqi_gender_preference', 'timezone',
                'notification_email', 'notification_sms', 'notification_push', 'notification_reminders',
                'primary_ailment', 'secondary_concerns', 'symptom_duration', 'symptom_intensity',
                'symptom_description', 'symptom_triggers', 'hallucinations', 'medical_checkup',
                'previous_ruqyah', 'current_medications', 'madhhab', 'previous_ruqyah_notes',
                'additional_notes', 'consent_ruqyah_shariah', 'consent_medical_disclaimer', 'consent_data_storage'
            ]);
        });
    }
};
