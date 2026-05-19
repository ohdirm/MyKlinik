<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: change ENUM to VARCHAR using raw SQL to avoid Doctrine dependency
        DB::statement('ALTER TABLE doctors MODIFY specialization VARCHAR(100) NOT NULL');
    }

    public function down(): void
    {
        // Restore to original ENUM — values not in the enum will be lost
        DB::statement("ALTER TABLE doctors MODIFY specialization ENUM(
            'UMUM',
            'SPESIALIS_ANAK',
            'SPESIALIS_KANDUNGAN',
            'SPESIALIS_PENYAKIT_DALAM',
            'SPESIALIS_BEDAH',
            'SPESIALIS_MATA',
            'SPESIALIS_THT',
            'SPESIALIS_KULIT',
            'SPESIALIS_JANTUNG'
        ) NOT NULL");
    }
};
