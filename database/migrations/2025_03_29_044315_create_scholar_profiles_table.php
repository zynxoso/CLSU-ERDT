<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholar_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Personal Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other', 'Prefer not to say']);
            $table->string('contact_number');
            
            // Home Address Structure
            $table->string('street')->nullable();
            $table->string('village')->nullable();
            $table->string('province')->nullable(); 
            $table->string('city')->nullable(); 
            $table->string('zipcode')->nullable();
            $table->string('district')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            
            // Educational Background - Made nullable
            $table->string('course_completed')->nullable();
            $table->string('university_graduated')->nullable();
            
            // Application Details - Made nullable
            $table->enum('entry_type', ['NEW', 'LATERAL'])->nullable();
            $table->string('intended_degree')->nullable();
            $table->string('intended_university')->nullable();
            $table->string('department')->nullable();
            $table->string('major')->nullable(); 
            $table->text('thesis_dissertation_title')->nullable();
            
            // Academic Load (for Lateral Entrants)
            $table->integer('units_required')->nullable();
            $table->integer('units_earned_prior')->nullable();
            
            // Scholarship Information
            $table->date('start_date')->nullable();
            $table->enum('enrollment_type', ['full_time', 'part_time'])->nullable();
            $table->integer('scholarship_duration')->nullable(); // in months
            $table->enum('scholar_status', ['active', 'graduated', 'deferred', 'dropped', 'inactive'])->default('active');
            
            // System fields
            $table->string('profile_photo')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('last_notified_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id']);
            $table->index(['scholar_status']);
            $table->index(['entry_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('scholar_profiles');
    }
};
