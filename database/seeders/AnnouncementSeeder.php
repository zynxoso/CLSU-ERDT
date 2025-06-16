<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Announcement;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Scholarship Application Deadline Extended',
                'content' => 'The deadline for new scholarship applications has been extended to March 15, 2024. Don\'t miss this opportunity to join the CLSU-ERDT program. All required documents must be submitted before the extended deadline.',
                'type' => 'urgent',
                'is_active' => true,
                'priority' => 10,
                'published_at' => now(),
                'expires_at' => null,
            ],
            [
                'title' => 'Monthly Stipend Schedule Update',
                'content' => 'January stipends will be released on January 25, 2024. Please ensure your bank details are updated in your scholar portal. Contact the admin office if you need assistance with updating your information.',
                'type' => 'application',
                'is_active' => true,
                'priority' => 8,
                'published_at' => now()->subDays(5),
                'expires_at' => null,
            ],
            [
                'title' => 'Annual ERDT Research Conference 2024',
                'content' => 'Registration is now open for the Annual ERDT Research Conference 2024. All scholars are encouraged to participate and present their research work. The conference will be held on March 15-17, 2024, at the CLSU Campus.',
                'type' => 'event',
                'is_active' => true,
                'priority' => 7,
                'published_at' => now()->subDays(10),
                'expires_at' => null,
            ],
            [
                'title' => 'New Research Grant Opportunities Available',
                'content' => 'We are pleased to announce additional research grant opportunities for current ERDT scholars. Grants up to ₱200,000 are available for innovative research projects in agricultural engineering, environmental sustainability, and smart technology applications.',
                'type' => 'scholarship',
                'is_active' => true,
                'priority' => 6,
                'published_at' => now()->subDays(15),
                'expires_at' => null,
            ],
            [
                'title' => 'System Maintenance Notice',
                'content' => 'The scholar portal will undergo scheduled maintenance on January 20, 2024, from 2:00 AM to 6:00 AM. Limited access may be expected during this period. We apologize for any inconvenience.',
                'type' => 'general',
                'is_active' => true,
                'priority' => 3,
                'published_at' => now()->subDays(20),
                'expires_at' => now()->addDays(5),
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
