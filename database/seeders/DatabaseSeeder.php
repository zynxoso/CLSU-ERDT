<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\RequestType;

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
        ]);

        if (app()->environment('local', 'development', 'testing')) {
            $this->seedTestData();
        }
    }

    /**
     * Seed test data for development and testing environments
     */
    private function seedTestData(): void
    {
        // Create some regular users that will be scholars
        \App\Models\User::factory(10)->create([
            'role' => 'scholar',
        ]);

        // Create test data
        $this->call([
            ScholarProfileSeeder::class,
            AnnouncementSeeder::class,
            ApplicationTimelineSeeder::class,
            FacultyMemberSeeder::class,
            ImportantNoteSeeder::class,
        ]);

        // Create a test scholar user
        $scholar = User::create([
            'name' => 'Test Scholar',
            'email' => 'scholar@example.com',
            'password' => Hash::make('password'),
            'role' => 'scholar',
            'is_active' => true,
        ]);

        // Create scholar profile
        $scholar->scholarProfile()->create([
            'first_name' => 'Test',
            'last_name' => 'Scholar',
            'contact_number' => '09123456789',
            'address' => '123 Main St',
            'university' => 'Central Luzon State University',
            'program' => 'MS in Agricultural Engineering',
            'status' => 'Ongoing',
            'start_date' => '2023-01-01',
            'expected_completion_date' => '2024-05-31',
        ]);
    }
}
