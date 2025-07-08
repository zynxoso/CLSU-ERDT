<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ImportantNote;

class ImportantNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        ImportantNote::truncate();

        $notesData = [
            [
                'title' => 'Application Submission Requirements',
                'content' => '<p><strong>All application documents must be submitted electronically and in hard copy.</strong></p>
                            <ul>
                                <li>Accomplished application form (downloadable from the website)</li>
                                <li>Official transcripts of records from all universities attended</li>
                                <li>Certificate of graduation or diploma</li>
                                <li>Two (2) letters of recommendation from academic references</li>
                                <li>Statement of purpose (2-3 pages)</li>
                                <li>Research proposal (for PhD applicants)</li>
                                <li>Birth certificate (NSO/PSA issued)</li>
                                <li>Medical certificate</li>
                                <li>2x2 ID photos (recent)</li>
                            </ul>',
                'type' => 'main',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Document Submission Instructions',
                'content' => '<p><strong>Electronic Submission:</strong></p>
                            <p>Submit scanned copies of all documents via email to: <strong>clsu.erdt@gmail.com</strong></p>
                            <p><strong>Hard Copy Submission:</strong></p>
                            <p>Mail original documents to:</p>
                            <p><strong>CLSU-ERDT Program<br>
                            Central Luzon State University<br>
                            Science City of Muñoz, Nueva Ecija 3120<br>
                            Philippines</strong></p>
                            <p><em>Note: Please ensure all documents are complete and properly organized before submission.</em></p>',
                'type' => 'submission',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Application Deadlines 2025',
                'content' => '<p><strong>CLSU-ERDT Application Deadlines:</strong></p>
                            <ul>
                                <li><strong>First Semester 2025:</strong> March 15, 2025 (11:59 PM)</li>
                                <li><strong>Second Semester 2025:</strong> September 15, 2025 (11:59 PM)</li>
                            </ul>
                            <p><strong>Other ERDT Member Universities:</strong></p>
                            <ul>
                                <li><strong>ADMU, UPD, UPLB, USC:</strong> 1st Sem - May 31, 2025; 2nd Sem - November 15, 2025</li>
                                <li><strong>DLSU:</strong> 1st Term - May 31, 2025; 2nd Term - October 30, 2025; 3rd Term - February 28, 2026</li>
                                <li><strong>MU:</strong> 1st Trimester - May 15, 2025; 2nd Trimester - October 15, 2025</li>
                                <li><strong>MSU-IIT:</strong> 1st Sem - April 30, 2025; 2nd Sem - October 15, 2025</li>
                            </ul>
                            <p><em>Late applications will not be accepted under any circumstances.</em></p>',
                'type' => 'deadline',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Eligibility Requirements',
                'content' => '<p><strong>General Eligibility Criteria:</strong></p>
                            <ul>
                                <li>Filipino citizen</li>
                                <li>Bachelor\'s degree in Engineering or related field</li>
                                <li>Minimum GPA of 2.25 (or equivalent)</li>
                                <li>Pass the qualifying examination</li>
                                <li>No age limit for MS; up to 35 years old for PhD</li>
                                <li>Physically and mentally fit to pursue graduate studies</li>
                                <li>Not currently receiving any other scholarship grant</li>
                            </ul>
                            <p><strong>Additional Requirements for PhD:</strong></p>
                            <ul>
                                <li>Master\'s degree in Engineering or related field</li>
                                <li>Research experience and publications (preferred)</li>
                                <li>Clear research proposal aligned with faculty expertise</li>
                            </ul>',
                'type' => 'main',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Scholarship Benefits',
                'content' => '<p><strong>ERDT Scholarship Package Includes:</strong></p>
                            <ul>
                                <li><strong>Monthly Stipend:</strong> ₱25,000 for PhD; ₱20,000 for MS</li>
                                <li><strong>Tuition and Fees:</strong> Full coverage</li>
                                <li><strong>Book Allowance:</strong> ₱10,000 per semester</li>
                                <li><strong>Thesis/Dissertation Grant:</strong> Up to ₱50,000</li>
                                <li><strong>Conference Presentation:</strong> Travel and registration support</li>
                                <li><strong>Research Equipment:</strong> Access to laboratory facilities</li>
                                <li><strong>Health Insurance:</strong> Medical coverage</li>
                            </ul>
                            <p><em>Scholarship duration: 2 years for MS, 4 years for PhD (with possible extension)</em></p>',
                'type' => 'main',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Contact Information',
                'content' => '<p><strong>For inquiries and assistance:</strong></p>
                            <p><strong>Email:</strong> clsu.erdt@gmail.com</p>
                            <p><strong>Phone:</strong> (044) 456-0731</p>
                            <p><strong>Office Address:</strong><br>
                            CLSU-ERDT Program Office<br>
                            Central Luzon State University<br>
                            Science City of Muñoz, Nueva Ecija 3120</p>
                            <p><strong>Office Hours:</strong> Monday to Friday, 8:00 AM - 5:00 PM</p>
                            <p><strong>Website:</strong> <a href="https://clsu-erdt.edu.ph" target="_blank">https://clsu-erdt.edu.ph</a></p>
                            <p><em>Please allow 24-48 hours for email responses during business days.</em></p>',
                'type' => 'submission',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($notesData as $data) {
            ImportantNote::create($data);
        }
    }
}
