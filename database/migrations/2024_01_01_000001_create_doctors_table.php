<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('specialization', [
                'UMUM',
                'SPESIALIS_ANAK',
                'SPESIALIS_KANDUNGAN',
                'SPESIALIS_PENYAKIT_DALAM',
                'SPESIALIS_BEDAH',
                'SPESIALIS_MATA',
                'SPESIALIS_THT',
                'SPESIALIS_KULIT',
                'SPESIALIS_JANTUNG',
            ]);
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
