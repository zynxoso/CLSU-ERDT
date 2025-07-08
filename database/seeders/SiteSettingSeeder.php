<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'CLSU-ERDT Scholar Management System',
                'type' => 'string',
                'group' => 'general',
                'description' => 'The name of the website/application',
                'is_public' => true,
            ],
            [
                'key' => 'site_description',
                'value' => 'Central Luzon State University - Engineering Research and Development for Technology Scholarship Management System',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Description of the website/application',
                'is_public' => true,
            ],

            // Academic Calendar Settings
            [
                'key' => 'academic_year',
                'value' => '2024-2025',
                'type' => 'string',
                'group' => 'academic',
                'description' => 'Current academic year',
                'is_public' => true,
            ],
            [
                'key' => 'first_semester_start',
                'value' => '2024-08-01',
                'type' => 'date',
                'group' => 'academic',
                'description' => 'First semester start date',
                'is_public' => true,
            ],
            [
                'key' => 'first_semester_end',
                'value' => '2024-12-15',
                'type' => 'date',
                'group' => 'academic',
                'description' => 'First semester end date',
                'is_public' => true,
            ],
            [
                'key' => 'second_semester_start',
                'value' => '2025-01-15',
                'type' => 'date',
                'group' => 'academic',
                'description' => 'Second semester start date',
                'is_public' => true,
            ],
            [
                'key' => 'second_semester_end',
                'value' => '2025-05-15',
                'type' => 'date',
                'group' => 'academic',
                'description' => 'Second semester end date',
                'is_public' => true,
            ],
            [
                'key' => 'summer_term_start',
                'value' => '2025-06-01',
                'type' => 'date',
                'group' => 'academic',
                'description' => 'Summer term start date',
                'is_public' => true,
            ],
            [
                'key' => 'summer_term_end',
                'value' => '2025-07-31',
                'type' => 'date',
                'group' => 'academic',
                'description' => 'Summer term end date',
                'is_public' => true,
            ],
            [
                'key' => 'application_deadline_1st',
                'value' => '2024-03-15 23:59:59',
                'type' => 'date',
                'group' => 'academic',
                'description' => 'First semester application deadline',
                'is_public' => true,
            ],
            [
                'key' => 'application_deadline_2nd',
                'value' => '2024-09-15 23:59:59',
                'type' => 'date',
                'group' => 'academic',
                'description' => 'Second semester application deadline',
                'is_public' => true,
            ],

            // Scholarship Parameters
            [
                'key' => 'max_monthly_allowance',
                'value' => '15000',
                'type' => 'integer',
                'group' => 'scholarship',
                'description' => 'Maximum monthly allowance in PHP',
                'is_public' => false,
            ],
            [
                'key' => 'max_tuition_support',
                'value' => '50000',
                'type' => 'integer',
                'group' => 'scholarship',
                'description' => 'Maximum tuition support in PHP',
                'is_public' => false,
            ],
            [
                'key' => 'max_research_allowance',
                'value' => '30000',
                'type' => 'integer',
                'group' => 'scholarship',
                'description' => 'Maximum research allowance in PHP',
                'is_public' => false,
            ],
            [
                'key' => 'max_book_allowance',
                'value' => '10000',
                'type' => 'integer',
                'group' => 'scholarship',
                'description' => 'Maximum book allowance in PHP',
                'is_public' => false,
            ],
            [
                'key' => 'max_scholarship_duration',
                'value' => '36',
                'type' => 'integer',
                'group' => 'scholarship',
                'description' => 'Maximum scholarship duration in months',
                'is_public' => false,
            ],
            [
                'key' => 'required_documents',
                'value' => 'Transcript of Records,Valid ID,Certificate of Enrollment,Grade Reports,Recommendation Letters',
                'type' => 'array',
                'group' => 'scholarship',
                'description' => 'Required documents for application',
                'is_public' => true,
            ],
            [
                'key' => 'require_entrance_exam',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'scholarship',
                'description' => 'Whether entrance examination is required',
                'is_public' => true,
            ],
            [
                'key' => 'require_interview',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'scholarship',
                'description' => 'Whether interview is required',
                'is_public' => true,
            ],

            // Email Settings
            [
                'key' => 'mail_driver',
                'value' => 'smtp',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Email driver (smtp, sendmail, mailgun)',
                'is_public' => false,
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.mailtrap.io',
                'type' => 'string',
                'group' => 'email',
                'description' => 'SMTP host server',
                'is_public' => false,
            ],
            [
                'key' => 'mail_port',
                'value' => '2525',
                'type' => 'integer',
                'group' => 'email',
                'description' => 'SMTP port number',
                'is_public' => false,
            ],
            [
                'key' => 'mail_username',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'description' => 'SMTP username',
                'is_public' => false,
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'description' => 'SMTP password',
                'is_public' => false,
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Email encryption (tls, ssl)',
                'is_public' => false,
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@clsu.edu.ph',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Default from email address',
                'is_public' => false,
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'CLSU-ERDT System',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Default from name',
                'is_public' => false,
            ],

            // Security Settings
            [
                'key' => 'password_expiry_days',
                'value' => '90',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Password expiry in days',
                'is_public' => false,
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Maximum login attempts before lockout',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
