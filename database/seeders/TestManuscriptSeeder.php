<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ScholarProfile;
use App\Models\Manuscript;

class TestManuscriptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the test scholar profile
        $scholarProfile = ScholarProfile::whereHas('user', function($query) {
            $query->where('email', 'test.scholar@example.com');
        })->first();

        if (!$scholarProfile) {
            $this->command->error('Test scholar profile not found. Please run the TestScholarSeeder first.');
            return;
        }

        // Create manuscripts with different statuses
        $statuses = ['Draft', 'Submitted', 'Under Review', 'Revision Requested', 'Accepted', 'Published', 'Rejected'];

        foreach ($statuses as $index => $status) {
            // Check if test manuscript already exists
            $existingManuscript = Manuscript::where('scholar_profile_id', $scholarProfile->id)
                ->where('title', 'Test Manuscript - ' . $status)
                ->first();

            if ($existingManuscript) {
                $this->command->info('Test manuscript for status "' . $status . '" already exists, skipping creation.');
            } else {
                // Create a test manuscript
                $manuscript = Manuscript::create([
                    'scholar_profile_id' => $scholarProfile->id,
                    'reference_number' => 'MS-' . date('Y') . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'title' => 'Test Manuscript - ' . $status,
                    'abstract' => 'This is a test abstract for manuscript with status "' . $status . '". It describes an innovative approach to agricultural engineering that could improve crop yields in Central Luzon region.',
                    'manuscript_type' => 'Journal Article',
                    'co_authors' => 'John Doe, Jane Smith',
                    'keywords' => 'agriculture, engineering, innovation, testing',
                    'status' => $status,
                    'admin_notes' => in_array($status, ['Under Review', 'Revision Requested', 'Accepted', 'Published', 'Rejected'])
                        ? 'This is admin feedback for the ' . $status . ' manuscript.'
                        : null,
                    'reviewed_by' => in_array($status, ['Revision Requested', 'Accepted', 'Published', 'Rejected']) ? 1 : null,
                    'reviewed_at' => in_array($status, ['Revision Requested', 'Accepted', 'Published', 'Rejected']) ? now() : null,
                ]);

                $this->command->info('Test manuscript created successfully: ' . $manuscript->title);
            }
        }

        $this->command->info('Test manuscript seeding completed.');
    }
}
