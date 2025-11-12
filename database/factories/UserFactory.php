<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $role = $this->faker->randomElement(['buyer', 'seller']);

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => $this->faker->phoneNumber(),
            'role' => $role,
            'store_name' => $role === 'seller' ? $this->faker->unique()->company() : null,
            'description' => $this->faker->text(200),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'store_verified_at' => $role === 'seller' ? now() : null,
            'last_login_at' => now()->subDays($this->faker->numberBetween(0, 30)),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
            'email_verification_token' => Str::random(64),
        ]);
    }

    /**
     * Create a buyer user.
     */
    public function buyer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'buyer',
            'store_name' => null,
            'store_verified_at' => null,
        ]);
    }

    /**
     * Create a seller user.
     */
    public function seller(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'seller',
            'store_name' => $this->faker->unique()->company(),
            'store_verified_at' => now(),
        ]);
    }
}
