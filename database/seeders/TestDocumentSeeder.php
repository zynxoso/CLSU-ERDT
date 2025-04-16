<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ScholarProfile;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TestDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the test scholar
        $scholar = User::where('email', 'test.scholar@example.com')->first();

        if (!$scholar) {
            $this->command->error('Test scholar not found. Please run TestScholarSeeder first.');
            return;
        }

        $scholarProfile = $scholar->scholarProfile;
        if (!$scholarProfile) {
            $this->command->error('Scholar profile not found for test scholar.');
            return;
        }

        // Find admin for verification
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $this->command->error('Admin user not found. Please run TestScholarSeeder first.');
            return;
        }

        // Create directory if it doesn't exist
        $documentPath = 'documents/' . $scholarProfile->id;
        if (!Storage::disk('public')->exists($documentPath)) {
            Storage::disk('public')->makeDirectory($documentPath);
        }

        // Helper function to create a test document
        $createDocument = function ($fileName, $category, $status, $description) use ($scholarProfile, $admin) {
            // Create a simple text file
            $content = "This is a test document for $fileName";
            $filePath = 'documents/' . $scholarProfile->id . '/' . $fileName . '.txt';

            Storage::disk('public')->put($filePath, $content);

            $document = new Document();
            $document->scholar_profile_id = $scholarProfile->id;
            $document->file_name = $fileName . '.txt';
            $document->file_path = $filePath;
            $document->file_type = 'text/plain';
            $document->file_size = strlen($content);
            $document->category = $category;
            $document->description = $description;
            $document->status = $status;

            if (in_array($status, ['Verified', 'Rejected'])) {
                $document->verified_by = $admin->id;
                $document->verified_at = now();
            }

            if ($status == 'Rejected') {
                $document->admin_notes = 'This document does not meet the requirements.';
            }

            $document->save();

            $this->command->info("Created document: $fileName with status $status");
        };

        // Create documents with different statuses
        $createDocument(
            'certificate_of_enrollment',
            'Certificate',
            'Pending',
            'Enrollment certificate for current semester'
        );

        $createDocument(
            'official_receipt',
            'Receipt',
            'Uploaded',
            'Payment receipt for tuition fee'
        );

        $createDocument(
            'transcript_of_records',
            'Transcript',
            'Verified',
            'Complete academic records from bachelor\'s degree'
        );

        $createDocument(
            'research_proposal',
            'Other',
            'Rejected',
            'Initial research proposal for thesis'
        );

        $createDocument(
            'university_id',
            'ID',
            'Verified',
            'Student identification card'
        );

        $this->command->info('Test documents seeded successfully.');
    }
}
