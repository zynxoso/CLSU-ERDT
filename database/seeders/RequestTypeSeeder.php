<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequestType;

class RequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requestTypes = [
            [
                'name' => 'Tuition Fee',
                'description' => 'Request for tuition fee reimbursement or payment',
                'is_active' => true
            ],
            [
                'name' => 'Stipend',
                'description' => 'Monthly stipend for living expenses',
                'is_active' => true
            ],
            [
                'name' => 'Learning Materials and Connectivity Allowance',
                'description' => 'Allowance for books, materials, and internet connectivity',
                'is_active' => true
            ],
            [
                'name' => 'Transportation Allowance',
                'description' => 'Allowance for transportation expenses',
                'is_active' => true
            ],
            [
                'name' => 'Thesis/Dissertation Outright Grant',
                'description' => 'Grant for thesis or dissertation expenses',
                'is_active' => true
            ],
            [
                'name' => 'Research Support Grant - Equipment',
                'description' => 'Grant for research equipment',
                'is_active' => true
            ],
            [
                'name' => 'Research Dissemination Grant',
                'description' => 'Grant for research publication and presentation',
                'is_active' => true
            ],
            [
                'name' => 'Mentor\'s Fee',
                'description' => 'Payment for thesis/dissertation mentor',
                'is_active' => true
            ]
        ];
        
        foreach ($requestTypes as $requestType) {
            RequestType::create($requestType);
        }
    }
}