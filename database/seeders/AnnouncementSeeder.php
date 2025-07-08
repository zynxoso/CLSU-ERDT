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
        // Clear existing data
        Announcement::truncate();

        $announcements = [
            [
                'title' => 'URGENT: ERDT Scholarship Applications Now Open for First Semester 2025',
                'content' => 'The CLSU-ERDT Program is now accepting applications for the First Semester 2025 intake! This is your opportunity to join the prestigious Engineering Research and Development for Technology (ERDT) scholarship program. Application period runs from January 15 to March 15, 2025. Don\'t miss this chance to advance your engineering career with full scholarship support including monthly stipends, tuition coverage, and research grants. Visit our How to Apply page for complete requirements and submission guidelines.',
                'type' => 'urgent',
                'is_active' => true,
                'priority' => 10,
                'published_at' => now(),
                'expires_at' => now()->addMonths(2),
            ],
            [
                'title' => 'Application Deadline Reminder - March 15, 2025 (11:59 PM)',
                'content' => 'Final reminder: All ERDT scholarship applications for First Semester 2025 must be submitted by March 15, 2025, at 11:59 PM. Late applications will not be accepted under any circumstances. Ensure all required documents are complete and submitted both electronically (clsu.erdt@gmail.com) and in hard copy to our office. Need help? Contact us at (044) 456-0731 during office hours.',
                'type' => 'urgent',
                'is_active' => true,
                'priority' => 9,
                'published_at' => now()->subDays(3),
                'expires_at' => now()->addDays(45),
            ],
            [
                'title' => 'ERDT Research Symposium 2025 - Call for Papers',
                'content' => 'The Annual ERDT Research Symposium 2025 will be held on April 15-17, 2025, at the CLSU Campus. We invite all current ERDT scholars and alumni to submit their research papers for presentation. This year\'s theme focuses on "Sustainable Engineering Solutions for Climate Resilience." Abstract submission deadline: February 28, 2025. Registration is free for all ERDT scholars. Awards will be given for outstanding research presentations.',
                'type' => 'event',
                'is_active' => true,
                'priority' => 8,
                'published_at' => now()->subDays(7),
                'expires_at' => now()->addMonths(3),
            ],
            [
                'title' => 'New Research Grant Opportunities - Up to PHP 300,000 Available',
                'content' => 'Exciting news! Additional research grants are now available for current ERDT scholars. Funding up to PHP 300,000 is available for innovative research projects in agricultural engineering, renewable energy, environmental sustainability, and smart agriculture technologies. Priority will be given to projects with potential for commercialization or significant societal impact. Application forms and guidelines are available in the scholar portal. Deadline for grant applications: February 15, 2025.',
                'type' => 'scholarship',
                'is_active' => true,
                'priority' => 7,
                'published_at' => now()->subDays(10),
                'expires_at' => now()->addDays(30),
            ],
            [
                'title' => 'January 2025 Stipend Release Schedule',
                'content' => 'Monthly stipends for January 2025 will be released on January 30, 2025. Please ensure your bank account details are updated in your scholar profile. Scholars who have not yet submitted their quarterly progress reports may experience delays in stipend release. For assistance with bank account updates or progress report submissions, please contact the ERDT office during business hours (8:00 AM - 5:00 PM, Monday to Friday).',
                'type' => 'general',
                'is_active' => true,
                'priority' => 6,
                'published_at' => now()->subDays(5),
                'expires_at' => now()->addDays(25),
            ],
            [
                'title' => 'Online Orientation for New ERDT Scholars - February 2025',
                'content' => 'All newly accepted ERDT scholars for the current academic year are required to attend the online orientation session scheduled for February 5, 2025, at 2:00 PM via Zoom. The orientation will cover scholarship guidelines, academic requirements, research expectations, financial procedures, and scholar responsibilities. Attendance is mandatory. Meeting details will be sent to your registered email address. For technical support, contact our IT helpdesk.',
                'type' => 'application',
                'is_active' => true,
                'priority' => 7,
                'published_at' => now()->subDays(2),
                'expires_at' => now()->addDays(20),
            ],
            [
                'title' => 'International Conference Participation Support Available',
                'content' => 'ERDT scholars are encouraged to present their research at international conferences. The program provides financial support for conference registration, travel, and accommodation expenses. Priority is given to scholars presenting in high-impact conferences related to their research field. Application for conference support should be submitted at least 60 days before the conference date. Maximum support of PHP 150,000 per scholar per academic year.',
                'type' => 'scholarship',
                'is_active' => true,
                'priority' => 5,
                'published_at' => now()->subDays(14),
                'expires_at' => null,
            ],
            [
                'title' => 'Scholar Portal Maintenance - January 28, 2025',
                'content' => 'The ERDT Scholar Portal will undergo scheduled maintenance on January 28, 2025, from 12:00 AM to 6:00 AM. During this period, the portal will be temporarily unavailable. This maintenance is necessary to improve system performance and add new features. We apologize for any inconvenience. For urgent matters during the maintenance window, please contact the ERDT office directly.',
                'type' => 'general',
                'is_active' => true,
                'priority' => 4,
                'published_at' => now()->subDays(1),
                'expires_at' => now()->addDays(10),
            ],
            [
                'title' => 'Second Semester 2025 Application Period Announcement',
                'content' => 'Mark your calendars! The application period for ERDT Scholarship Second Semester 2025 will open on July 15, 2025, and close on September 15, 2025. We encourage prospective applicants to start preparing their documents early. Information sessions will be conducted in major universities across the Philippines starting June 2025. Follow our social media pages and website for updates on information session schedules and locations.',
                'type' => 'application',
                'is_active' => true,
                'priority' => 3,
                'published_at' => now()->subDays(12),
                'expires_at' => null,
            ],
            [
                'title' => 'ERDT Alumni Success Stories - Featured Publication',
                'content' => 'We are proud to feature the success stories of our ERDT alumni in our latest publication. From groundbreaking research to innovative startups, our graduates continue to make significant contributions to the engineering field and society. Read inspiring stories of career achievements, research breakthroughs, and entrepreneurial ventures. The publication is available for download on our website and in the scholar portal.',
                'type' => 'general',
                'is_active' => true,
                'priority' => 2,
                'published_at' => now()->subDays(20),
                'expires_at' => null,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
