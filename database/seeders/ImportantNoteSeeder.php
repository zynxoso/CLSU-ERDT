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
        $notesData = [
            [
                'title' => 'Application Submission',
                'content' => '<p><strong>Accomplished application form and other documentary requirements must be submitted through e-mail of the ERDT Project staff of the desired ERDT member-university on or before the deadline.</strong></p>',
                'type' => 'main',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Hard Copy Submission',
                'content' => '<p><strong>Hard copies of same must be sent through mail:</strong></p><p>Please scan all documents before mailing them.</p>',
                'type' => 'submission',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Application Deadlines',
                'content' => '<p><strong>When should I apply? Are there deadlines?</strong></p>
                            <ul>
                                <li><strong>ADMU, UPD, UPLB, USC:</strong> 1st Sem - May 31, 2025; 2nd Sem - November 15, 2025</li>
                                <li><strong>CLSU:</strong> 1st Sem - June 15, 2025; 2nd Sem - November 15, 2025</li>
                                <li><strong>DLSU:</strong> 1st Term - May 31, 2025; 2nd Term - October 30, 2025; 3rd Term - February 28, 2026</li>
                                <li><strong>MU:</strong> 1st Trimester - May 15, 2025; 2nd Trimester - October 15, 2025</li>
                                <li><strong>MSU-IIT:</strong> 1st Sem - April 30, 2025; 2nd Sem - October 15, 2025</li>
                            </ul>',
                'type' => 'deadline',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($notesData as $data) {
            ImportantNote::create($data);
        }
    }
}
