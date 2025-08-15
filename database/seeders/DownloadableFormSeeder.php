<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DownloadableForm;
use App\Models\User;
use Illuminate\Support\Facades\File;

class DownloadableFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get super admin user for uploaded_by field
        $superAdmin = User::where('role', 'super_admin')->first();
        $uploadedBy = $superAdmin ? $superAdmin->id : null;

        // Path to forms directory
        $formsPath = public_path('forms');
        
        if (!File::exists($formsPath)) {
            $this->command->warn('Forms directory does not exist: ' . $formsPath);
            return;
        }

        // Get all files from forms directory
        $files = File::files($formsPath);
        
        if (empty($files)) {
            $this->command->warn('No files found in forms directory');
            return;
        }

        // Define form categories and their mapping to display categories
        $formCategories = [
            'application' => [
                'keywords' => ['application', 'apply', 'form', 'scholarship', 'grant'],
                'display_name' => 'Essential Application Forms'
            ],
            'research' => [
                'keywords' => ['research', 'proposal', 'thesis', 'dissertation', 'study'],
                'display_name' => 'Research & Grant Forms'
            ],
            'administrative' => [
                'keywords' => ['liquidation', 'report', 'monitoring', 'evaluation', 'progress', 'completion', 'certificate', 'undertaking', 'agreement'],
                'display_name' => 'Administrative & Monitoring Forms'
            ]
        ];

        $sortOrder = 1;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $filePath = 'forms/' . $filename;
            $fileSize = $file->getSize();
            
            // Get MIME type using PHP's built-in function
            $fullPath = $file->getRealPath();
            $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';
            
            // Skip if already exists
            if (DownloadableForm::where('filename', $filename)->exists()) {
                $this->command->info("Skipping existing file: {$filename}");
                continue;
            }

            // Determine category based on filename
            $category = $this->categorizeFile($filename, $formCategories);
            
            // Generate title and description from filename
            $title = $this->generateTitle($filename);
            $description = $this->generateDescription($filename, $category);

            // Create downloadable form record
            DownloadableForm::create([
                'title' => $title,
                'description' => $description,
                'filename' => $filename,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'category' => $category,
                'status' => true,
                'download_count' => 0,
                'uploaded_by' => $uploadedBy,
                'sort_order' => $sortOrder++
            ]);

            $this->command->info("Added: {$title} ({$category})");
        }

        $this->command->info('Downloadable forms seeding completed!');
    }

    /**
     * Categorize file based on filename keywords
     */
    private function categorizeFile(string $filename, array $categories): string
    {
        $lowerFilename = strtolower($filename);
        
        foreach ($categories as $category => $config) {
            foreach ($config['keywords'] as $keyword) {
                if (strpos($lowerFilename, $keyword) !== false) {
                    return $category;
                }
            }
        }
        
        // Default to application if no category matches
        return 'application';
    }

    /**
     * Generate human-readable title from filename
     */
    private function generateTitle(string $filename): string
    {
        // Remove file extension
        $title = pathinfo($filename, PATHINFO_FILENAME);
        
        // Replace hyphens and underscores with spaces
        $title = str_replace(['-', '_'], ' ', $title);
        
        // Convert to title case
        $title = ucwords(strtolower($title));
        
        // Handle common abbreviations
        $title = str_replace([
            'Erdt', 'Clsu', 'Pdf', 'Doc', 'Docx', 'Xlsx',
            'Ched', 'Dost', 'Sei', 'Asthrdp'
        ], [
            'ERDT', 'CLSU', 'PDF', 'DOC', 'DOCX', 'XLSX',
            'CHED', 'DOST', 'SEI', 'ASTHRDP'
        ], $title);
        
        return $title;
    }

    /**
     * Generate description based on filename and category
     */
    private function generateDescription(string $filename, string $category): string
    {
        $lowerFilename = strtolower($filename);
        
        // Generate specific descriptions based on file content indicators
        if (strpos($lowerFilename, 'application') !== false) {
            return 'Application form for ERDT scholarship program. Please fill out completely and submit with required documents.';
        }
        
        if (strpos($lowerFilename, 'liquidation') !== false) {
            return 'Financial liquidation report form for scholarship grants and research funding.';
        }
        
        if (strpos($lowerFilename, 'progress') !== false || strpos($lowerFilename, 'monitoring') !== false) {
            return 'Progress monitoring and evaluation form for ongoing research projects and scholarships.';
        }
        
        if (strpos($lowerFilename, 'completion') !== false || strpos($lowerFilename, 'certificate') !== false) {
            return 'Completion certificate and final report form for finished research projects.';
        }
        
        if (strpos($lowerFilename, 'proposal') !== false || strpos($lowerFilename, 'research') !== false) {
            return 'Research proposal submission form with guidelines and requirements.';
        }
        
        if (strpos($lowerFilename, 'thesis') !== false || strpos($lowerFilename, 'dissertation') !== false) {
            return 'Thesis or dissertation related form for graduate students and researchers.';
        }
        
        if (strpos($lowerFilename, 'undertaking') !== false || strpos($lowerFilename, 'agreement') !== false) {
            return 'Undertaking agreement form outlining responsibilities and commitments.';
        }
        
        // Default descriptions by category
        switch ($category) {
            case 'application':
                return 'Essential application form for ERDT programs and scholarships.';
            case 'research':
                return 'Research and grant related form for academic and scientific activities.';
            case 'administrative':
                return 'Administrative form for monitoring, reporting, and compliance purposes.';
            default:
                return 'Official form document for ERDT programs and activities.';
        }
    }
}