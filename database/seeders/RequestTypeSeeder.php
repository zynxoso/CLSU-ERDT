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
                'name' => 'TF and other school fees',
                'description' => 'Tuition fees and other school-related fees',
                'required_documents' => json_encode(['Registration Form/Enrollment Form']),
                'document_frequency' => 'semester',
                'document_guidance' => 'Submit official registration or enrollment form from your university.',
                'max_amount_masters' => 50000.00,
                'max_amount_doctoral' => 80000.00,
                'frequency' => 'semester',
                'is_requestable' => true,
                'special_requirements' => null,
                'is_active' => true
            ],
            [
                'name' => 'Learning Materials',
                'description' => 'Books, supplies, and learning materials allowance',
                'required_documents' => json_encode(['Receipts', 'List of Materials']),
                'document_frequency' => 'semester',
                'document_guidance' => 'Provide receipts and detailed list of learning materials purchased.',
                'max_amount_masters' => 15000.00,
                'max_amount_doctoral' => 20000.00,
                'frequency' => 'semester',
                'is_requestable' => true,
                'special_requirements' => null,
                'is_active' => true
            ],
            [
                'name' => 'Transportation',
                'description' => 'Transportation allowance for academic activities',
                'required_documents' => json_encode(['Travel Documentation', 'Receipts']),
                'document_frequency' => 'monthly',
                'document_guidance' => 'Submit travel receipts and documentation of academic-related trips.',
                'max_amount_masters' => 8000.00,
                'max_amount_doctoral' => 10000.00,
                'frequency' => 'monthly',
                'is_requestable' => true,
                'special_requirements' => null,
                'is_active' => true
            ],
            [
                'name' => 'Research Dissemination Grant',
                'description' => 'Grant for research publication and conference presentation',
                'required_documents' => json_encode(['Conference Registration', 'Abstract/Paper', 'Travel Documents']),
                'document_frequency' => 'per_event',
                'document_guidance' => 'Provide conference registration, accepted abstract/paper, and travel documentation.',
                'max_amount_masters' => 30000.00,
                'max_amount_doctoral' => 50000.00,
                'frequency' => 'per_event',
                'is_requestable' => true,
                'special_requirements' => json_encode(['Must have accepted paper or presentation']),
                'is_active' => true
            ],
            [
                'name' => 'Mentor\'s Fee',
                'description' => 'Payment for thesis/dissertation mentor',
                'required_documents' => json_encode(['Mentorship Agreement', 'Progress Report']),
                'document_frequency' => 'semester',
                'document_guidance' => 'Submit signed mentorship agreement and progress reports.',
                'max_amount_masters' => 25000.00,
                'max_amount_doctoral' => 40000.00,
                'frequency' => 'semester',
                'is_requestable' => true,
                'special_requirements' => json_encode(['Requires approved mentor', 'Progress milestone completion']),
                'is_active' => true
            ],
            [
                'name' => 'Stipend',
                'description' => 'Monthly living allowance for scholars',
                'required_documents' => json_encode(['Enrollment Verification', 'Academic Progress Report']),
                'document_frequency' => 'semester',
                'document_guidance' => 'Automatic processing based on enrollment status and academic progress.',
                'max_amount_masters' => 15000.00,
                'max_amount_doctoral' => 20000.00,
                'frequency' => 'monthly',
                'is_requestable' => false,
                'special_requirements' => json_encode(['Automatic processing', 'Enrollment verification required']),
                'is_active' => true
            ],
            [
                'name' => 'Thesis/Dissertation Grant',
                'description' => 'One-time grant for thesis or dissertation expenses',
                'required_documents' => json_encode(['Research Proposal', 'Budget Breakdown', 'Advisor Approval']),
                'document_frequency' => 'once',
                'document_guidance' => 'Submit approved research proposal with detailed budget and advisor endorsement.',
                'max_amount_masters' => 40000.00,
                'max_amount_doctoral' => 60000.00,
                'frequency' => 'once',
                'is_requestable' => true,
                'special_requirements' => json_encode(['Approved research proposal', 'Advisor endorsement required']),
                'is_active' => true
            ],
            [
                'name' => 'Research Equipment Grant',
                'description' => 'Grant for research equipment and tools',
                'required_documents' => json_encode(['Equipment Quotation', 'Research Justification', 'Advisor Approval']),
                'document_frequency' => 'per_request',
                'document_guidance' => 'Provide equipment quotations, research justification, and advisor approval.',
                'max_amount_masters' => 35000.00,
                'max_amount_doctoral' => 50000.00,
                'frequency' => 'per_request',
                'is_requestable' => true,
                'special_requirements' => json_encode(['Equipment must be research-related', 'Advisor approval required']),
                'is_active' => true
            ]
        ];
        
        foreach ($requestTypes as $requestType) {
            RequestType::create($requestType);
        }
    }
}