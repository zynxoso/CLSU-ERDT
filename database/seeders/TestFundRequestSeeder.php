<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FundRequest;
use App\Models\RequestType;
use App\Models\ScholarProfile;

class TestFundRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find our test scholar profile
        $scholarProfile = ScholarProfile::whereHas('user', function($query) {
            $query->where('email', 'test.scholar@example.com');
        })->first();

        if (!$scholarProfile) {
            $this->command->info('Test scholar not found. Please run TestScholarSeeder first.');
            return;
        }

        // Create or find the request type
        $requestType = RequestType::firstOrCreate(
            ['name' => 'Research Materials'],
            [
                'description' => 'Funding for research materials and supplies',
                'is_active' => true,
            ]
        );

        // Create test fund requests with different statuses
        $statuses = ['Draft', 'Submitted', 'Under Review', 'Approved', 'Rejected'];

        foreach ($statuses as $status) {
            $existingRequest = FundRequest::where('scholar_profile_id', $scholarProfile->id)
                ->where('status', $status)
                ->where('purpose', 'Test ' . $status . ' Request')
                ->first();

            if (!$existingRequest) {
                FundRequest::create([
                    'scholar_profile_id' => $scholarProfile->id,
                    'request_type_id' => $requestType->id,
                    'amount' => rand(5000, 20000),
                    'purpose' => 'Test ' . $status . ' Request',
                    'status' => $status,
                    'admin_notes' => $status === 'Rejected' ? 'This is a test rejection reason.' :
                                    ($status === 'Under Review' ? 'Currently under review by the committee.' : null),
                    'reviewed_by' => ($status === 'Approved' || $status === 'Rejected') ? 1 : null,
                    'reviewed_at' => ($status === 'Approved' || $status === 'Rejected') ? now() : null,
                ]);

                $this->command->info('Created test fund request with status: ' . $status);
            } else {
                $this->command->info('Test fund request with status ' . $status . ' already exists, skipping creation.');
            }
        }

        $this->command->info('Test fund requests seeded successfully.');
    }
}
