<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Enhanced fields from enhance_request_types_table migration
            $table->json('required_documents')->nullable()->comment('JSON array of required document types');
            $table->enum('document_frequency', ['once', 'per_request', 'annual', 'semester', 'monthly', 'per_event'])->default('per_request')->comment('How often documents are required');
            $table->text('document_guidance')->nullable()->comment('Instructions for document submission');
            $table->decimal('max_amount_masters', 10, 2)->nullable()->comment('Maximum amount for Masters students');
            $table->decimal('max_amount_doctoral', 10, 2)->nullable()->comment('Maximum amount for Doctoral students');
            $table->enum('frequency', ['monthly', 'quarterly', 'semester', 'annual', 'one_time', 'once', 'per_event', 'per_request'])->default('one_time')->comment('Request frequency allowed');
            $table->boolean('is_requestable')->default(true)->comment('Whether this type can be requested by scholars');
            $table->text('special_requirements')->nullable()->comment('Additional requirements or conditions');
            $table->decimal('max_amount', 10, 2)->nullable()->comment('General maximum amount (fallback)');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_types');
    }
};