<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->unique()->constrained('doctors')->cascadeOnDelete();
            $table->enum('current_status', [
                'AVAILABLE',
                'IN_EXAMINATION',
                'NEXT_AVAILABLE',
                'UNAVAILABLE',
            ])->default('AVAILABLE');
            $table->integer('current_queue_number')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_statuses');
    }
};
