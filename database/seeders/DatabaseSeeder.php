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
        // Call other seeders
        $this->call([
            SuperAdminUserSeeder::class,
            FacultyMemberSeeder::class,
        ]);

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@clsu-erdt.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create a test scholar user
        $scholar = User::create([
            'name' => 'Test Scholar',
            'email' => 'scholar@example.com',
            'password' => Hash::make('password'),
            'role' => 'scholar',
            'email_verified_at' => now(),
        ]);

        // Create scholar profile
        $scholar->scholarProfile()->create([
            'first_name' => 'Test',
            'last_name' => 'Scholar',
            'gender' => 'Male',
            'birth_date' => '1995-01-01',
            'contact_number' => '09123456789',
            'address' => '123 Main St',
            'city' => 'Science City of Muñoz',
            'province' => 'Nueva Ecija',
            'postal_code' => '3120',
            'university' => 'Central Luzon State University',
            'degree_program' => 'MS in Agricultural Engineering',
            'year_level' => '2nd Year',
            'expected_graduation' => '2024-05-31',
            'status' => 'Ongoing',
            'scholar_id' => 'ERDT-2023-001',
        ]);

        // Create request types
        $requestTypes = [
            [
                'name' => 'Tuition Fee',
                'description' => 'Request for tuition fee reimbursement or payment',
                'required_documents' => ['Enrollment Form', 'Official Receipt', 'Certificate of Registration'],
                'max_amount' => 50000.00,
            ],
            [
                'name' => 'Stipend',
                'description' => 'Monthly stipend for living expenses',
                'required_documents' => ['Enrollment Certificate', 'Progress Report'],
                'max_amount' => 20000.00,
            ],
            [
                'name' => 'Learning Materials and Connectivity Allowance',
                'description' => 'Allowance for books, materials, and internet connection',
                'required_documents' => ['Receipts', 'Justification Letter'],
                'max_amount' => 10000.00,
            ],
            [
                'name' => 'Transportation Allowance',
                'description' => 'Allowance for transportation expenses',
                'required_documents' => ['Travel Itinerary', 'Receipts'],
                'max_amount' => 5000.00,
            ],
            [
                'name' => 'Thesis/Dissertation Outright Grant',
                'description' => 'Grant for thesis or dissertation research',
                'required_documents' => ['Research Proposal', 'Budget Proposal', 'Adviser Endorsement'],
                'max_amount' => 100000.00,
            ],
            [
                'name' => 'Research Support Grant - Equipment',
                'description' => 'Grant for research equipment',
                'required_documents' => ['Equipment Specifications', 'Quotations', 'Justification Letter'],
                'max_amount' => 50000.00,
            ],
            [
                'name' => 'Research Dissemination Grant',
                'description' => 'Grant for conference attendance or publication fees',
                'required_documents' => ['Conference Acceptance Letter', 'Abstract', 'Budget Proposal'],
                'max_amount' => 30000.00,
            ],
            [
                'name' => 'Mentor\'s Fee',
                'description' => 'Fee for thesis/dissertation mentor',
                'required_documents' => ['Mentor Agreement', 'Progress Report'],
                'max_amount' => 15000.00,
            ],
        ];

        foreach ($requestTypes as $type) {
            RequestType::create($type);
        }
    }
}
