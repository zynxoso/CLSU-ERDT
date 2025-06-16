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
        Schema::create('history_timeline_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('event_date');
            $table->string('year_label')->nullable(); // For display like "2007", "2020+"
            $table->string('category')->default('milestone'); // milestone, achievement, partnership
            $table->string('icon')->nullable(); // CSS class or icon name
            $table->string('color')->default('blue'); // Color theme
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_timeline_items');
    }
};
