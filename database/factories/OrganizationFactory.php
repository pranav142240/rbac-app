<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->paragraph(),
            'settings' => [
                'timezone' => fake()->timezone(),
                'locale' => fake()->randomElement(['en', 'es', 'fr']),
                'max_users' => fake()->numberBetween(10, 1000),
            ],
        ];
    }

    /**
     * Create a small organization
     */
    public function small(): static
    {
        return $this->state(fn (array $attributes) => [
            'settings' => array_merge($attributes['settings'] ?? [], [
                'max_users' => fake()->numberBetween(5, 25),
            ]),
        ]);
    }

    /**
     * Create a large organization
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'settings' => array_merge($attributes['settings'] ?? [], [
                'max_users' => fake()->numberBetween(500, 5000),
            ]),
        ]);
    }
}
