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
            if (!Schema::hasColumn('scholar_profiles', 'major')) {
                $table->string('major')->nullable()->after('department');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholar_profiles', function (Blueprint $table) {
            $table->dropColumn('major');
        });
    }
};
