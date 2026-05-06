<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained()->restrictOnDelete();
            $table->date('booking_date');
            $table->time('preferred_start')->nullable(); // Rentang waktu pilihan pasien
            $table->time('preferred_end')->nullable();
            $table->unsignedSmallInteger('queue_number');
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable(); // Keluhan awal pasien
            $table->timestamps();

            // Satu pasien tidak bisa booking jadwal yang sama pada tanggal yang sama
            $table->unique(['user_id', 'schedule_id', 'booking_date']);
            // Nomor antrian unik per jadwal per tanggal
            $table->unique(['schedule_id', 'booking_date', 'queue_number']);

            $table->index(['schedule_id', 'booking_date', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
