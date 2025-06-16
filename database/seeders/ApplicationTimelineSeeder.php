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
        $timelineData = [
            [
                'activity' => 'Call for Applications',
                'first_semester' => 'January 15 - February 28',
                'second_semester' => 'July 15 - August 31',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'activity' => 'Screening and Evaluation',
                'first_semester' => 'March 1 - March 31',
                'second_semester' => 'September 1 - September 30',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'activity' => 'Announcement of Results',
                'first_semester' => 'April 15',
                'second_semester' => 'October 15',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'activity' => 'Scholarship Orientation',
                'first_semester' => 'May 1',
                'second_semester' => 'November 1',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'activity' => 'Start of Classes',
                'first_semester' => 'June',
                'second_semester' => 'December',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($timelineData as $data) {
            ApplicationTimeline::create($data);
        }
    }
}
