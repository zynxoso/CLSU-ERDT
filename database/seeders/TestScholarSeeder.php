<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ScholarProfile;
use Illuminate\Support\Facades\Hash;

class TestScholarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test scholar user if doesn't exist
        $scholarEmail = 'test.scholar@example.com';
        $user = User::where('email', $scholarEmail)->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Test Scholar',
                'email' => $scholarEmail,
                'password' => Hash::make('password'),
                'role' => 'scholar',
            ]);
            $this->command->info('Test scholar user created.');

            // Create scholar profile
            ScholarProfile::create([
                'user_id' => $user->id,
                'first_name' => 'Test',
                'middle_name' => 'M',
                'last_name' => 'Scholar',
                'contact_number' => '+63 912 345 6789',
                'address' => '123 Test Street',
                'city' => 'Science City of Muñoz',
                'province' => 'Nueva Ecija',
                'postal_code' => '3120',
                'country' => 'Philippines',
                'program' => 'Master in Agricultural and Biosystems Engineering',
                'university' => 'Central Luzon State University',
                'department' => 'Engineering',
                'status' => 'Ongoing',
                'start_date' => now()->subYear(),
                'expected_completion_date' => now()->addYear(),
                'bachelor_degree' => 'BS in Agricultural and Biosystems Engineering',
                'bachelor_university' => 'Central Luzon State University',
                'bachelor_graduation_year' => now()->subYears(2)->year,
                'research_area' => 'Agricultural Machinery and Equipment',
            ]);
            $this->command->info('Test scholar profile created.');
        } else {
            $this->command->info('Test scholar already exists, skipping creation.');
        }

        // Create user for admin role if doesn't exist
        $adminEmail = 'admin@clsu-erdt.edu.ph';
        $adminUser = User::where('email', $adminEmail)->first();

        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
            $this->command->info('Test admin created.');
        } else {
            $this->command->info('Test admin already exists, skipping creation.');
        }

        $this->command->info('Test data seeding completed.');
    }
}
