<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('manuscripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_profile_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('abstract');
            $table->enum('status', ['Outline Submitted', 'Outline Approved', 'Draft Submitted', 'Under Review', 'Revision Requested', 'Accepted', 'Published'])->default('Outline Submitted');
            $table->text('reviewer_comments')->nullable();
            $table->foreignId('reviewed_by')->nullable()->references('id')->on('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('manuscripts');
    }
};