<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'scholar', // Default role
            'is_active' => true,
            'last_login_at' => null,
            'last_login_ip' => null,
            'password_expires_at' => now()->addDays(90), // Default from SiteSettings
            'password_changed_at' => now(),
            'must_change_password' => false,
            'is_default_password' => false,
            'email_notifications' => true,
            'fund_request_notifications' => true,
            'document_notifications' => true,
            'manuscript_notifications' => true,
        ];
    }

    /**
     * Configure the model factory for inactive users.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Configure the model factory for admin users.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Configure the model factory for super admin users.
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'super_admin',
        ]);
    }

    /**
     * Configure the model factory for users with expired passwords.
     */
    public function passwordExpired(): static
    {
        return $this->state(fn (array $attributes) => [
            'password_expires_at' => now()->subDay(),
            'must_change_password' => true,
        ]);
    }
}
