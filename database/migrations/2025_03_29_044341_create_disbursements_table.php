<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_request_id')->constrained()->onDelete('cascade');
            $table->string('reference_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->date('disbursement_date');
            $table->enum('status', ['Pending', 'Processed', 'Completed', 'Cancelled'])->default('Pending');
            $table->foreignId('processed_by')->nullable()->references('id')->on('users');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('disbursements');
    }
};