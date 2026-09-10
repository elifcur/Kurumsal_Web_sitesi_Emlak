<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'title' => rtrim(fake()->unique()->sentence(3), '.'),
            'sku' => strtoupper(fake()->bothify('PRD-####')),
            'excerpt' => fake()->sentence(14),
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(2)).'</p>',
            'price' => fake()->randomFloat(2, 100, 5000),
            'specs' => [
                ['label' => 'Malzeme', 'value' => fake()->word()],
                ['label' => 'Garanti', 'value' => '2 Yıl'],
            ],
            'sort_order' => 0,
            'is_active' => true,
            'is_featured' => fake()->boolean(30),
        ];
    }
}
