<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('fund_request_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('manuscript_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('status')->default('active');
            $table->boolean('security_scanned')->default(false);
            $table->timestamp('security_scanned_at')->nullable();
            $table->string('security_scan_result')->nullable();
            $table->string('file_type');
            $table->integer('file_size');
            $table->string('category');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->json('security_context')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
