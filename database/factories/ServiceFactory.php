<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => rtrim(fake()->unique()->sentence(3), '.'),
            'excerpt' => fake()->sentence(14),
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(3)).'</p>',
            'icon' => fake()->randomElement(['briefcase', 'cog', 'shield', 'chart', 'wrench', 'star']),
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
