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
        Schema::create('history_content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // introduction, vision, hero, etc.
            $table->string('key'); // title, subtitle, content, etc.
            $table->text('value');
            $table->string('type')->default('text'); // text, html, image, url
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['section', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_content_blocks');
    }
};
