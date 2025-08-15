<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder 
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin users first 
        $this->call([
            SuperAdminUserSeeder::class,
            AdminUserSeeder::class,
            RequestTypeSeeder::class,
            SiteSettingSeeder::class,
            FacultyMemberSeeder::class,
            AnnouncementSeeder::class,
            ApplicationTimelineSeeder::class,
            AnnouncementSeeder::class,
            ImportantNoteSeeder::class,
            DownloadableFormSeeder::class,
        ]);    
    }

}
