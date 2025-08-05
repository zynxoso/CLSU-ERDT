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
            $table->foreignId('scholar_profile_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('reference_number')->nullable()->unique();
            $table->string('title');
            $table->text('abstract');
            $table->enum('manuscript_type', ['Outline', 'Final'])->default('Outline');
            $table->string('co_authors')->nullable();
            $table->string('keywords')->nullable();
            $table->enum('status', ['Draft', 'Submitted', 'Under Review', 'Revision Requested', 'Accepted', 'Published', 'Rejected'])->default('Draft');
            $table->text('admin_notes')->nullable();
            $table->text('reviewer_comments')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->json('security_context')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('manuscripts');
    }
};
