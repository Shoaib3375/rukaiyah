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
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignUuid('lead_raqi_id')->references('id')->on('raqi_profiles');
            $table->enum('session_type', ['video', 'chat', 'phone', 'in_person']);
            $table->enum('status', ['pending','confirmed','cancelled','completed','no_show'])->default('pending');
            $table->timestamp('scheduled_at');
            $table->unsignedInteger('duration_minutes')->default(60);
            $table->text('patient_notes')->nullable();
            $table->text('raqi_notes')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
