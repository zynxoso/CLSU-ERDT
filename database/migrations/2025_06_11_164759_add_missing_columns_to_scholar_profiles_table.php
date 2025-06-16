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
            // Add missing columns that are in the model but not in the database
            if (!Schema::hasColumn('scholar_profiles', 'major')) {
                $table->string('major')->nullable()->after('degree_level');
            }
            if (!Schema::hasColumn('scholar_profiles', 'phone')) {
                $table->string('phone')->nullable()->after('contact_number');
            }
            if (!Schema::hasColumn('scholar_profiles', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('major');
            }
            if (!Schema::hasColumn('scholar_profiles', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('profile_photo');
            }
            if (!Schema::hasColumn('scholar_profiles', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('scholar_profiles', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }
            if (!Schema::hasColumn('scholar_profiles', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('verified_at');
            }
            if (!Schema::hasColumn('scholar_profiles', 'scholar_id')) {
                $table->string('scholar_id')->nullable()->unique()->after('admin_notes');
            }
        });

        // Add foreign key constraint for verified_by if column exists and constraint doesn't exist
        if (Schema::hasColumn('scholar_profiles', 'verified_by')) {
            try {
                Schema::table('scholar_profiles', function (Blueprint $table) {
                    $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist, ignore the error
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholar_profiles', function (Blueprint $table) {
            // Drop foreign key first if it exists
            try {
                $table->dropForeign(['verified_by']);
            } catch (\Exception $e) {
                // Foreign key might not exist, ignore the error
            }

            // Drop columns if they exist
            $columnsToCheck = [
                'major', 'phone', 'profile_photo', 'is_verified',
                'verified_by', 'verified_at', 'admin_notes', 'scholar_id'
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('scholar_profiles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
