<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fund_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_profile_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('request_type_id');
            $table->decimal('amount', 10, 2);
            $table->text('purpose');
            $table->enum('status', ['Draft', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Disbursed'])->default('Draft');
            $table->text('admin_remarks')->nullable();
            $table->foreignId('reviewed_by')->nullable()->references('id')->on('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fund_requests');
    }
};