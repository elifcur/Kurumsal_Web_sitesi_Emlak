<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->slug(2);

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'file_name' => $name.'.jpg',
            'disk' => 'uploads',
            'path' => 'media/'.date('Y/m').'/'.$name.'.jpg',
            'thumb_path' => 'media/'.date('Y/m').'/thumbs/'.$name.'.jpg',
            'mime_type' => 'image/jpeg',
            'size' => fake()->numberBetween(10000, 500000),
            'width' => 1200,
            'height' => 800,
            'collection' => 'default',
        ];
    }
}
