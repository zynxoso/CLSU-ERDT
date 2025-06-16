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
            // Add research_title and research_abstract fields if they don't exist
            if (!Schema::hasColumn('scholar_profiles', 'research_title')) {
                $table->string('research_title')->nullable()->after('research_area');
            }
            if (!Schema::hasColumn('scholar_profiles', 'research_abstract')) {
                $table->text('research_abstract')->nullable()->after('research_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholar_profiles', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('scholar_profiles', 'research_title')) {
                $table->dropColumn('research_title');
            }
            if (Schema::hasColumn('scholar_profiles', 'research_abstract')) {
                $table->dropColumn('research_abstract');
            }
        });
    }
};
