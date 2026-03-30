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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('raqi_id')->references('id')->on('raqi_profiles')->cascadeOnDelete();
            $table->tinyInteger('day_of_week'); // 0=Monday ... 6=Sunday
            $table->time('slot_start');
            $table->time('slot_end');
            $table->boolean('is_blocked')->default(false);
            $table->timestamps();
            $table->unique(['raqi_id', 'day_of_week', 'slot_start']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
