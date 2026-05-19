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
        Schema::create('specializations', function (Blueprint $table) {
            $table->id();
            $table->string('value')->unique(); // e.g. UMUM, SPESIALIS_ANAK
            $table->string('label');           // e.g. Umum, Spesialis Anak
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Seed default specializations
        $defaults = [
            ['value' => 'UMUM',                    'label' => 'Umum'],
            ['value' => 'SPESIALIS_ANAK',           'label' => 'Spesialis Anak'],
            ['value' => 'SPESIALIS_KANDUNGAN',      'label' => 'Spesialis Kandungan'],
            ['value' => 'SPESIALIS_PENYAKIT_DALAM', 'label' => 'Spesialis Penyakit Dalam'],
            ['value' => 'SPESIALIS_BEDAH',          'label' => 'Spesialis Bedah'],
            ['value' => 'SPESIALIS_MATA',           'label' => 'Spesialis Mata'],
            ['value' => 'SPESIALIS_THT',            'label' => 'Spesialis THT'],
            ['value' => 'SPESIALIS_KULIT',          'label' => 'Spesialis Kulit'],
            ['value' => 'SPESIALIS_JANTUNG',        'label' => 'Spesialis Jantung'],
        ];

        foreach ($defaults as $spec) {
            \Illuminate\Support\Facades\DB::table('specializations')->insert([
                'value'      => $spec['value'],
                'label'      => $spec['label'],
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specializations');
    }
};
