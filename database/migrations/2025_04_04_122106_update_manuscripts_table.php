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
        Schema::table('manuscripts', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('manuscripts', 'reference_number')) {
                $table->string('reference_number')->nullable()->unique()->after('scholar_profile_id');
            }

            if (!Schema::hasColumn('manuscripts', 'manuscript_type')) {
                $table->enum('manuscript_type', ['Conference Paper', 'Journal Article', 'Thesis', 'Dissertation', 'Book Chapter', 'Other'])
                    ->default('Journal Article')
                    ->after('abstract');
            }

            if (!Schema::hasColumn('manuscripts', 'co_authors')) {
                $table->string('co_authors')->nullable()->after('manuscript_type');
            }

            if (!Schema::hasColumn('manuscripts', 'keywords')) {
                $table->string('keywords')->nullable()->after('co_authors');
            }

            if (!Schema::hasColumn('manuscripts', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('status');
            }

            // If status column exists but has wrong enum values, consider modifying it
            // (this is more complex and may require dropping and recreating the column)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manuscripts', function (Blueprint $table) {
            $table->dropColumn([
                'reference_number',
                'manuscript_type',
                'co_authors',
                'keywords',
                'admin_notes'
            ]);
        });
    }
};
