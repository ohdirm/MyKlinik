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
            $table->string('booking_code', 10)->unique();
            $table->string('patient_name');
            $table->string('nik', 16);
            $table->string('phone', 15);
            $table->date('birth_date');
            $table->enum('gender', ['L', 'P']);
            $table->text('address');
            $table->string('province');
            $table->string('district');
            $table->string('sub_district');
            $table->string('village');
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->date('exam_date');
            $table->integer('queue_number');
            $table->enum('status', ['PENDING', 'CONFIRMED', 'REJECTED', 'DONE', 'CANCELLED'])->default('PENDING');
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
