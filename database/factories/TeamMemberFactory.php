<?php

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'title' => fake()->jobTitle(),
            'excerpt' => fake()->sentence(12),
            'bio' => '<p>'.implode('</p><p>', fake()->paragraphs(2)).'</p>',
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'socials' => ['linkedin' => 'https://linkedin.com/in/'.fake()->userName()],
            'attributes' => [],
            'sort_order' => fake()->numberBetween(0, 20),
            'is_active' => true,
            'is_featured' => fake()->boolean(40),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
