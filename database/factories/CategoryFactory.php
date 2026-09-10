<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => 'product',
            'name' => rtrim(fake()->unique()->words(2, true), '.'),
            'description' => fake()->sentence(8),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }

    public function post(): static
    {
        return $this->state(fn () => ['type' => 'post']);
    }
}
