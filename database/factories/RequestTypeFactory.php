<?php

namespace Database\Factories;

use App\Models\RequestType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestType>
 */
class RequestTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RequestType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $requestTypes = [
            'TF and other school fees',
            'Learning Materials',
            'Transportation',
            'Research Dissemination Grant',
            'Mentor\'s Fee',
            'Thesis/Dissertation Grant',
            'Book Allowance',
            'Monthly Stipend'
        ];

        $name = $this->faker->randomElement($requestTypes);
        
        return [
            'name' => $name,
            'description' => $this->faker->sentence(10),
            'required_documents' => $this->getDocumentsForType($name),
            'document_frequency' => $this->faker->randomElement(['semester', 'monthly', 'per_event', 'annual']),
            'document_guidance' => $this->faker->paragraph(),
            'max_amount_masters' => $this->faker->randomFloat(2, 5000, 50000),
            'max_amount_doctoral' => $this->faker->randomFloat(2, 10000, 100000),
            'frequency' => $this->faker->randomElement(['semester', 'monthly', 'quarterly', 'annual']),
            'is_requestable' => $this->faker->boolean(80), // 80% chance of being requestable
            'special_requirements' => $this->faker->optional()->randomElements([
                'Must be enrolled',
                'Minimum GPA requirement',
                'Research proposal required',
                'Supervisor approval needed'
            ], $this->faker->numberBetween(0, 2)),
            'max_amount' => $this->faker->randomFloat(2, 1000, 75000),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Get appropriate documents for the request type.
     */
    private function getDocumentsForType(string $type): array
    {
        $documentMap = [
            'TF and other school fees' => ['Registration Form', 'Enrollment Form'],
            'Learning Materials' => ['Official Receipt', 'List of Materials'],
            'Transportation' => ['Transportation Receipts', 'Travel Itinerary'],
            'Research Dissemination Grant' => ['Conference Registration', 'Abstract/Paper'],
            'Mentor\'s Fee' => ['Mentorship Agreement', 'Progress Report'],
            'Thesis/Dissertation Grant' => ['Research Proposal', 'Supervisor Approval'],
            'Book Allowance' => ['Book List', 'Purchase Receipts'],
            'Monthly Stipend' => ['Enrollment Certificate', 'Academic Progress Report']
        ];

        return $documentMap[$type] ?? ['General Document', 'Supporting Document'];
    }

    /**
     * Indicate that the request type is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the request type is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the request type is requestable by scholars.
     */
    public function requestable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_requestable' => true,
        ]);
    }

    /**
     * Indicate that the request type is not requestable by scholars.
     */
    public function notRequestable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_requestable' => false,
        ]);
    }

    /**
     * Create a tuition fee request type.
     */
    public function tuitionFee(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'TF and other school fees',
            'description' => 'Tuition fees and other school-related expenses',
            'required_documents' => ['Registration Form', 'Enrollment Form'],
            'document_frequency' => 'semester',
            'max_amount_masters' => 25000.00,
            'max_amount_doctoral' => 35000.00,
            'frequency' => 'semester',
            'is_requestable' => true,
            'is_active' => true,
        ]);
    }

    /**
     * Create a research dissemination grant request type.
     */
    public function researchGrant(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Research Dissemination Grant',
            'description' => 'Grant for presenting research at conferences',
            'required_documents' => ['Conference Registration', 'Abstract/Paper', 'Travel Documents'],
            'document_frequency' => 'per_event',
            'max_amount_masters' => 50000.00,
            'max_amount_doctoral' => 75000.00,
            'frequency' => 'per_event',
            'is_requestable' => true,
            'is_active' => true,
        ]);
    }
}