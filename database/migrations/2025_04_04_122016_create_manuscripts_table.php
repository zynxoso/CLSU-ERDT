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
        Schema::create('manuscripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_profile_id')->constrained()->onDelete('cascade');
            $table->string('reference_number')->nullable()->unique();
            $table->string('title');
            $table->text('abstract');
            $table->enum('manuscript_type', ['Conference Paper', 'Journal Article', 'Thesis', 'Dissertation', 'Book Chapter', 'Other'])->default('Journal Article');
            $table->string('co_authors')->nullable();
            $table->string('keywords')->nullable();
            $table->enum('status', ['Draft', 'Submitted', 'Under Review', 'Revision Requested', 'Accepted', 'Published', 'Rejected'])->default('Draft');
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->references('id')->on('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manuscripts');
    }
};
