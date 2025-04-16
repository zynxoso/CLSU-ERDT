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
        Schema::table('scholar_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('scholar_profiles', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('scholar_profiles', 'province')) {
                $table->string('province')->nullable();
            }
            if (!Schema::hasColumn('scholar_profiles', 'postal_code')) {
                $table->string('postal_code')->nullable();
            }
            if (!Schema::hasColumn('scholar_profiles', 'country')) {
                $table->string('country')->nullable()->default('Philippines');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholar_profiles', function (Blueprint $table) {
            $table->dropColumn(['city', 'province', 'postal_code', 'country']);
        });
    }
};
