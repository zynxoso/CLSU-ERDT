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
        Schema::table('fund_requests', function (Blueprint $table) {
            // Add encrypted financial fields only if they don't exist
            if (!Schema::hasColumn('fund_requests', 'bank_account_number')) {
                $table->text('bank_account_number')->nullable()->after('details');
            }
            if (!Schema::hasColumn('fund_requests', 'bank_name')) {
                $table->text('bank_name')->nullable()->after('bank_account_number');
            }
            if (!Schema::hasColumn('fund_requests', 'account_holder_name')) {
                $table->text('account_holder_name')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('fund_requests', 'routing_number')) {
                $table->text('routing_number')->nullable()->after('account_holder_name');
            }
            if (!Schema::hasColumn('fund_requests', 'tax_identification_number')) {
                $table->text('tax_identification_number')->nullable()->after('routing_number');
            }
            if (!Schema::hasColumn('fund_requests', 'tax_identification_hash')) {
                $table->string('tax_identification_hash')->nullable()->index()->after('tax_identification_number');
            }
            if (!Schema::hasColumn('fund_requests', 'amount_breakdown')) {
                $table->text('amount_breakdown')->nullable()->after('tax_identification_hash');
            }

            // Add missing columns that should exist based on the model
            if (!Schema::hasColumn('fund_requests', 'reference_number')) {
                $table->string('reference_number')->nullable()->unique()->after('request_type_id');
            }
            if (!Schema::hasColumn('fund_requests', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed'])->default('pending')->after('amount_breakdown');
            }
            if (!Schema::hasColumn('fund_requests', 'status_history')) {
                $table->json('status_history')->nullable()->after('status');
            }
            if (!Schema::hasColumn('fund_requests', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('status_history');
            }
            if (!Schema::hasColumn('fund_requests', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('admin_notes');
            }
            if (!Schema::hasColumn('fund_requests', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
        });

        // Add foreign key constraint for reviewed_by if it doesn't exist
        if (Schema::hasColumn('fund_requests', 'reviewed_by')) {
            try {
                Schema::table('fund_requests', function (Blueprint $table) {
                    $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist, ignore the error
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund_requests', function (Blueprint $table) {
            // Drop foreign key first
            try {
                $table->dropForeign(['reviewed_by']);
            } catch (\Exception $e) {
                // Ignore if foreign key doesn't exist
            }

            // Drop columns if they exist
            $columnsToCheck = [
                'bank_account_number', 'bank_name', 'account_holder_name', 'routing_number',
                'tax_identification_number', 'tax_identification_hash', 'amount_breakdown',
                'reference_number', 'status', 'status_history', 'admin_notes', 'reviewed_by', 'reviewed_at'
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('fund_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
