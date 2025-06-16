<?php

namespace Database\Factories;

use App\Models\OrganizationGroup;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrganizationGroup>
 */
class OrganizationGroupFactory extends Factory
{
    protected $model = OrganizationGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'name' => fake()->words(2, true) . ' Group',
            'description' => fake()->sentence(),
        ];
    }

    /**
     * Create a group for a specific organization
     */
    public function forOrganization(Organization $organization): static
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $organization->id,
        ]);
    }
}
