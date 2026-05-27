<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('profile_type', ['self', 'family'])->default('self')->after('user_id');
            $table->foreignId('family_profile_id')->nullable()->after('profile_type')
                ->constrained('family_profiles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['family_profile_id']);
            $table->dropColumn(['profile_type', 'family_profile_id']);
        });
    }
};
