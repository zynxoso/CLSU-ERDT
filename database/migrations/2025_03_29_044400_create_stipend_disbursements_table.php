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
        Schema::create('stipend_disbursements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholar_profile_id');
            $table->string('reference_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('disbursement_type'); // monthly, special, adjustment
            $table->date('disbursement_date');
            $table->string('period_covered'); // e.g., "January 2025", "Q1 2025"
            $table->text('description')->nullable();
            $table->enum('status', ['Pending', 'Processed', 'Completed', 'Failed'])->default('Pending');
            $table->json('calculation_details')->nullable(); // Store calculation breakdown
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->json('notification_status')->nullable(); // Track notification delivery
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['scholar_profile_id', 'disbursement_date']);
            $table->index(['status', 'disbursement_date']);
            $table->index('reference_number');
        });
        
        // Add foreign key constraints after table creation
        Schema::table('stipend_disbursements', function (Blueprint $table) {
            $table->foreign('scholar_profile_id')->references('id')->on('scholar_profiles')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stipend_disbursements');
    }
};