<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update stipend/living allowance request types to be non-requestable by scholars
        // This prevents scholars from directly requesting stipends through the fund request system
        // Stipends should be processed separately through the monthly stipend disbursement system
        
        DB::table('request_types')
            ->where('name', 'LIKE', '%stipend%')
            ->orWhere('name', 'LIKE', '%living allowance%')
            ->orWhere('name', 'LIKE', '%monthly allowance%')
            ->update(['is_requestable' => false]);
            
        // Also update by ID if we know the specific ID (from FundRequestService.php, ID 2 is Living Allowance/Stipend)
        DB::table('request_types')
            ->where('id', 2)
            ->update(['is_requestable' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes - make stipend types requestable again
        DB::table('request_types')
            ->where('name', 'LIKE', '%stipend%')
            ->orWhere('name', 'LIKE', '%living allowance%')
            ->orWhere('name', 'LIKE', '%monthly allowance%')
            ->update(['is_requestable' => true]);
            
        DB::table('request_types')
            ->where('id', 2)
            ->update(['is_requestable' => true]);
    }
};