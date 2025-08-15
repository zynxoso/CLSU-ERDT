<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ScholarProfile;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScholarProfile>
 */
class ScholarProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScholarProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional(0.7)->firstName(),
            'last_name' => $this->faker->lastName(),
            'suffix' => $this->faker->optional(0.2)->randomElement(['Jr.', 'Sr.', 'III', 'IV']),
            'birth_date' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other', 'Prefer not to say']),
            'contact_number' => $this->faker->phoneNumber(),
            'street' => $this->faker->streetAddress(),
            'village' => $this->faker->optional(0.8)->city(),
            'town' => $this->faker->city(),
            'province' => $this->faker->state(),
            'zipcode' => $this->faker->postcode(),
            'district' => $this->faker->optional(0.6)->randomElement(['District I', 'District II', 'District III', 'District IV']),
            'region' => $this->faker->optional(0.8)->randomElement(['Region I', 'Region II', 'Region III', 'Region IV', 'NCR']),
            'country' => $this->faker->country(),
            'course_completed' => $this->faker->randomElement([
                'Bachelor of Science in Computer Science',
                'Bachelor of Science in Information Technology',
                'Bachelor of Science in Engineering',
                'Bachelor of Arts in Psychology',
                'Bachelor of Science in Biology',
                'Bachelor of Science in Mathematics'
            ]),
            'university_graduated' => $this->faker->randomElement([
                'University of the Philippines',
                'Ateneo de Manila University',
                'De La Salle University',
                'University of Santo Tomas',
                'Central Luzon State University'
            ]),
            'entry_type' => $this->faker->randomElement(['NEW', 'LATERAL']),
            'intended_degree' => $this->faker->randomElement([
                'Master of Science in Computer Science',
                'Master of Science in Information Technology',
                'Doctor of Philosophy in Computer Science',
                'Master of Arts in Psychology',
                'Doctor of Philosophy in Biology'
            ]),
            'level' => $this->faker->randomElement(['MS', 'PHD']),
            'intended_university' => $this->faker->randomElement([
                'University of the Philippines',
                'Ateneo de Manila University',
                'De La Salle University',
                'University of Santo Tomas',
                'Central Luzon State University'
            ]),
            'department' => $this->faker->randomElement([
                'Computer Science',
                'Information Technology',
                'Engineering',
                'Psychology',
                'Biology',
                'Mathematics'
            ]),
            'major' => $this->faker->optional(0.8)->randomElement([
                'Software Engineering',
                'Data Science',
                'Artificial Intelligence',
                'Cybersecurity',
                'Web Development'
            ]),
            'thesis_dissertation_title' => $this->faker->optional(0.6)->sentence(8),
            'units_required' => $this->faker->optional(0.4)->numberBetween(30, 60),
            'units_earned_prior' => $this->faker->optional(0.4)->numberBetween(0, 30),
            'start_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'study_time' => $this->faker->randomElement(['Full-time', 'Part-time']),
            'scholarship_duration' => $this->faker->numberBetween(24, 60),
            'scholar_status' => $this->faker->randomElement(['active', 'graduated', 'deferred', 'dropped', 'inactive']),
            'is_verified' => $this->faker->boolean(70),
            'verified_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 year', 'now'),
            'verified_by' => $this->faker->optional(0.7)->randomElement([null, User::factory()]),
            'actual_completion_date' => $this->faker->optional(0.3)->dateTimeBetween('-1 year', '+2 years'),
            'notes' => $this->faker->optional(0.4)->paragraph(),
            'last_notified_at' => $this->faker->optional(0.5)->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the scholar profile is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
            'verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the scholar profile is unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => false,
            'verified_at' => null,
        ]);
    }
}