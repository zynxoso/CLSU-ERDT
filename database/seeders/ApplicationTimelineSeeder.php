<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApplicationTimeline;

class ApplicationTimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        ApplicationTimeline::truncate();

        $timelineData = [
            [
                'activity' => 'Call for Applications / Application Period Opens',
                'first_semester' => 'January 15 - March 15',
                'second_semester' => 'July 15 - September 15',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'activity' => 'Online Application Submission Deadline',
                'first_semester' => 'March 15, 11:59 PM',
                'second_semester' => 'September 15, 11:59 PM',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'activity' => 'Document Verification and Initial Screening',
                'first_semester' => 'March 16 - March 31',
                'second_semester' => 'September 16 - September 30',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'activity' => 'Academic Evaluation and Assessment',
                'first_semester' => 'April 1 - April 30',
                'second_semester' => 'October 1 - October 31',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'activity' => 'Interview and Final Evaluation',
                'first_semester' => 'May 1 - May 15',
                'second_semester' => 'November 1 - November 15',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'activity' => 'Deliberation and Selection Process',
                'first_semester' => 'May 16 - May 31',
                'second_semester' => 'November 16 - November 30',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'activity' => 'Announcement of Scholarship Results',
                'first_semester' => 'June 1 - June 15',
                'second_semester' => 'December 1 - December 15',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'activity' => 'Scholarship Award and Contract Signing',
                'first_semester' => 'June 16 - June 30',
                'second_semester' => 'December 16 - December 31',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'activity' => 'Pre-Enrollment and Orientation',
                'first_semester' => 'July 1 - July 15',
                'second_semester' => 'January 1 - January 15',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'activity' => 'Start of Classes / Scholarship Implementation',
                'first_semester' => 'August (1st Semester)',
                'second_semester' => 'February (2nd Semester)',
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'activity' => 'Quarterly Progress Monitoring',
                'first_semester' => 'Throughout Academic Year',
                'second_semester' => 'Throughout Academic Year',
                'sort_order' => 11,
                'is_active' => true,
            ],
            [
                'activity' => 'Mid-Year Performance Evaluation',
                'first_semester' => 'December - January',
                'second_semester' => 'June - July',
                'sort_order' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($timelineData as $data) {
            ApplicationTimeline::create($data);
        }
    }
}
