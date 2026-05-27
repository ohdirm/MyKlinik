<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('full_name');
            $table->enum('relationship', ['Ayah', 'Ibu', 'Anak', 'Saudara', 'Suami/Istri', 'Lainnya']);
            $table->string('nik', 16);
            $table->date('birth_date');
            $table->enum('gender', ['L', 'P']);
            $table->string('phone_number', 15);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_profiles');
    }
};
