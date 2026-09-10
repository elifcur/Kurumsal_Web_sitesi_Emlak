<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => rtrim($title, '.'),
            'excerpt' => fake()->sentence(12),
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(3)).'</p>',
            'template' => 'default',
            'is_published' => true,
            'published_at' => now(),
            'show_in_menu' => false,
            'sort_order' => 0,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['is_published' => false, 'published_at' => null]);
    }
}
