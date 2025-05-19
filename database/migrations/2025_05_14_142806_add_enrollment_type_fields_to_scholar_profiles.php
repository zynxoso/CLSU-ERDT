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
            $table->enum('enrollment_type', ['New', 'Lateral'])->default('New')->after('status');
            $table->enum('study_time', ['Full-time', 'Part-time'])->default('Full-time')->after('enrollment_type');
            $table->integer('scholarship_duration')->nullable()->after('study_time')->comment('Duration in months');
            $table->string('degree_level')->nullable()->after('program')->comment('MS or PhD');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholar_profiles', function (Blueprint $table) {
            $table->dropColumn(['enrollment_type', 'study_time', 'scholarship_duration', 'degree_level']);
        });
    }
};
