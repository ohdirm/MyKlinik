<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('max_patients')->default(20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Mencegah jadwal duplikat untuk dokter pada hari yang sama
            $table->unique(['doctor_id', 'day_of_week', 'start_time']);
            $table->index(['doctor_id', 'day_of_week', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
