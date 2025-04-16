<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('scholar_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('contact_number');
            $table->text('address');
            $table->string('university');
            $table->string('program');
            $table->string('department')->nullable();
            $table->enum('status', ['New', 'Ongoing', 'On Extension', 'Graduated', 'Terminated', 'Deferred Repayment'])->default('New');
            $table->date('start_date');
            $table->date('expected_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->string('bachelor_degree')->nullable();
            $table->string('bachelor_university')->nullable();
            $table->integer('bachelor_graduation_year')->nullable();
            $table->string('research_area')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scholar_profiles');
    }
};
