<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarProfile;
use App\Models\User;
use Illuminate\Support\Str;

class ScholarProfileSeeder extends Seeder
{
    public function run(): void
    {
        // Get some users to assign as scholars
        $users = User::where('role', 'scholar')->take(10)->get();

        foreach ($users as $user) {
            ScholarProfile::create([
                'user_id' => $user->id,
                'first_name' => fake()->firstName(),
                'middle_name' => fake()->randomElement(['A.', 'B.', null]),
                'last_name' => fake()->lastName(),
                'contact_number' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'university' => fake()->company(),
                'program' => fake()->word(),
                'department' => fake()->word(),
                'status' => fake()->randomElement(['New', 'Ongoing', 'On Extension', 'Graduated', 'Terminated', 'Deferred Repayment']),
                'start_date' => fake()->date('Y-m-d', '-3 years'),
                'expected_completion_date' => fake()->date('Y-m-d', '+1 years'),
                'actual_completion_date' => null,
                'bachelor_degree' => fake()->word(),
                'bachelor_university' => fake()->company(),
                'bachelor_graduation_year' => fake()->year('-5 years'),
                'research_area' => fake()->words(3, true),
                'notes' => fake()->paragraph()
            ]);
        }
    }
}
