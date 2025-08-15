<?php

namespace Database\Factories;

use App\Models\FundRequest;
use App\Models\ScholarProfile;
use App\Models\RequestType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FundRequest>
 */
class FundRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FundRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'scholar_profile_id' => ScholarProfile::factory(),
            'request_type_id' => RequestType::factory(),
            'amount' => $this->faker->randomFloat(2, 1000, 50000),
            'purpose' => $this->faker->sentence(8),
            'status' => $this->faker->randomElement([
    
                'Submitted', 
                'Under Review',
                'Approved',
                'Rejected',
                'Disbursed'
            ]),
            'status_history' => $this->faker->optional(0.3)->randomElement([
                null,
                json_encode([
                    [
                        'status' => 'Submitted',
                        'changed_at' => $this->faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
                        'changed_by' => 1
                    ]
                ])
            ]),
            'admin_remarks' => $this->faker->optional(0.4)->paragraph(),
            'reviewed_by' => $this->faker->optional(0.6)->randomElement([null, \App\Models\User::factory()]),
            'reviewed_at' => $this->faker->optional(0.6)->dateTimeBetween('-1 month', 'now'),

        ];
    }

    /**
     * Indicate that the fund request is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Submitted',
            'reviewed_by' => null,
            'reviewed_at' => null,
            'admin_remarks' => null,
        ]);
    }

    /**
     * Indicate that the fund request is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Approved',
            'reviewed_by' => \App\Models\User::factory(),
            'reviewed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'admin_remarks' => $this->faker->optional(0.7)->sentence(),
        ]);
    }

    /**
     * Indicate that the fund request is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Rejected',
            'admin_remarks' => $this->faker->sentence(),
            'reviewed_by' => \App\Models\User::factory(),
            'reviewed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the fund request is disbursed.
     */
    public function disbursed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Disbursed',
            'reviewed_by' => \App\Models\User::factory(),
            'reviewed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'admin_remarks' => $this->faker->optional(0.7)->sentence(),
        ]);
    }

    /**
     * Indicate that the fund request is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Disbursed',
            'reviewed_by' => \App\Models\User::factory(),
            'reviewed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'admin_remarks' => 'Request completed successfully.',
        ]);
    }

    /**
     * Create a fund request for tuition fees.
     */
    public function forTuitionFees(): static
    {
        return $this->state(fn (array $attributes) => [
            'request_type_id' => RequestType::factory()->tuitionFee(),
            'amount' => $this->faker->randomFloat(2, 15000, 35000),
            'purpose' => 'Tuition fees and school expenses for current semester',
        ]);
    }

    /**
     * Create a fund request for research dissemination.
     */
    public function forResearchDissemination(): static
    {
        return $this->state(fn (array $attributes) => [
            'request_type_id' => RequestType::factory()->researchGrant(),
            'amount' => $this->faker->randomFloat(2, 25000, 75000),
            'purpose' => 'Conference presentation and research dissemination',
        ]);
    }

    /**
     * Create a fund request with specific scholar profile.
     */
    public function forScholar(ScholarProfile $scholar): static
    {
        return $this->state(fn (array $attributes) => [
            'scholar_profile_id' => $scholar->id,
        ]);
    }

    /**
     * Create a fund request with specific request type.
     */
    public function withRequestType(RequestType $requestType): static
    {
        return $this->state(fn (array $attributes) => [
            'request_type_id' => $requestType->id,
        ]);
    }

    /**
     * Create a fund request with specific amount.
     */
    public function withAmount(float $amount): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $amount,
        ]);
    }
}