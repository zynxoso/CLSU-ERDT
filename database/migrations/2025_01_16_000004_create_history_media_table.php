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
        Schema::create('history_media', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path');
            $table->string('file_type'); // image, video, document
            $table->string('mime_type');
            $table->integer('file_size');
            $table->string('alt_text')->nullable();
            $table->text('description')->nullable();
            $table->string('usage')->nullable(); // hero, timeline, achievement, etc.
            $table->json('metadata')->nullable(); // dimensions, duration, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_media');
    }
};
