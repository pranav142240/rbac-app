<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

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
            'phone' => fake()->unique()->e164PhoneNumber(),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'primary_auth_method' => fake()->randomElement([
                'email_password',
                'email_otp',
                'phone_password',
                'phone_otp'
            ]),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a user with email authentication
     */
    public function emailAuth(): static
    {
        return $this->state(fn (array $attributes) => [
            'primary_auth_method' => 'email_password',
        ]);
    }

    /**
     * Create a user with phone authentication
     */
    public function phoneAuth(): static
    {
        return $this->state(fn (array $attributes) => [
            'primary_auth_method' => 'phone_password',
        ]);
    }

    /**
     * Create a user with OTP authentication
     */
    public function otpAuth(): static
    {
        return $this->state(fn (array $attributes) => [
            'primary_auth_method' => fake()->randomElement(['email_otp', 'phone_otp']),
        ]);
    }

    /**
     * Configure the model factory to assign default role after creation
     */
    public function configure()
    {
        return $this->afterCreating(function (\App\Models\User $user) {
            // Assign default "Application User" role if user has no roles
            if ($user->roles()->count() === 0) {
                $applicationUserRole = Role::where('name', 'Application User')->first();
                if ($applicationUserRole) {
                    $user->assignRole($applicationUserRole);
                }
            }
        });
    }
}
