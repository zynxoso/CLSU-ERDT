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
            // Only add columns that don't exist yet
            if (!Schema::hasColumn('scholar_profiles', 'government_id')) {
                $table->text('government_id')->nullable()->after('program');
            }
            if (!Schema::hasColumn('scholar_profiles', 'government_id_type')) {
                $table->string('government_id_type')->nullable()->after('government_id');
            }
            if (!Schema::hasColumn('scholar_profiles', 'tax_id')) {
                $table->text('tax_id')->nullable()->after('government_id_type');
            }
            if (!Schema::hasColumn('scholar_profiles', 'medical_information')) {
                $table->text('medical_information')->nullable()->after('tax_id');
            }
            if (!Schema::hasColumn('scholar_profiles', 'emergency_contact')) {
                $table->text('emergency_contact')->nullable()->after('medical_information');
            }
            if (!Schema::hasColumn('scholar_profiles', 'emergency_contact_phone')) {
                $table->text('emergency_contact_phone')->nullable()->after('emergency_contact');
            }
            if (!Schema::hasColumn('scholar_profiles', 'government_id_hash')) {
                $table->string('government_id_hash')->nullable()->index()->after('emergency_contact_phone');
            }
            if (!Schema::hasColumn('scholar_profiles', 'tax_id_hash')) {
                $table->string('tax_id_hash')->nullable()->index()->after('government_id_hash');
            }

            // Add missing columns that should exist based on the model
            if (!Schema::hasColumn('scholar_profiles', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('program');
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
            if (!Schema::hasColumn('scholar_profiles', 'start_date')) {
                $table->date('start_date')->nullable()->after('scholar_id');
            }
            if (!Schema::hasColumn('scholar_profiles', 'expected_completion_date')) {
                $table->date('expected_completion_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('scholar_profiles', 'actual_completion_date')) {
                $table->date('actual_completion_date')->nullable()->after('expected_completion_date');
            }
            if (!Schema::hasColumn('scholar_profiles', 'bachelor_degree')) {
                $table->string('bachelor_degree')->nullable()->after('actual_completion_date');
            }
            if (!Schema::hasColumn('scholar_profiles', 'bachelor_university')) {
                $table->string('bachelor_university')->nullable()->after('bachelor_degree');
            }
            if (!Schema::hasColumn('scholar_profiles', 'bachelor_graduation_year')) {
                $table->integer('bachelor_graduation_year')->nullable()->after('bachelor_university');
            }
            if (!Schema::hasColumn('scholar_profiles', 'research_area')) {
                $table->string('research_area')->nullable()->after('bachelor_graduation_year');
            }
            if (!Schema::hasColumn('scholar_profiles', 'notes')) {
                $table->text('notes')->nullable()->after('research_area');
            }
            if (!Schema::hasColumn('scholar_profiles', 'scholarship_duration')) {
                $table->integer('scholarship_duration')->nullable()->after('notes');
            }
        });

        // Add foreign key constraint for verified_by if it doesn't exist
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
            // Drop foreign key first
            try {
                $table->dropForeign(['verified_by']);
            } catch (\Exception $e) {
                // Ignore if foreign key doesn't exist
            }

            // Drop columns if they exist
            $columnsToCheck = [
                'government_id', 'government_id_type', 'tax_id', 'medical_information',
                'emergency_contact', 'emergency_contact_phone', 'government_id_hash', 'tax_id_hash',
                'profile_photo', 'is_verified', 'verified_by', 'verified_at', 'admin_notes',
                'scholar_id', 'start_date', 'expected_completion_date', 'actual_completion_date',
                'bachelor_degree', 'bachelor_university', 'bachelor_graduation_year',
                'research_area', 'notes', 'scholarship_duration'
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('scholar_profiles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
