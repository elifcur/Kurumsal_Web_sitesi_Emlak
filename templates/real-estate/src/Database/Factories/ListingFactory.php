<?php

namespace Templates\RealEstate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Templates\RealEstate\Models\Listing;

/**
 * @extends Factory<Listing>
 */
class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['sale', 'rent']);

        return [
            'title' => rtrim(fake()->unique()->sentence(4), '.'),
            'listing_type' => $type,
            'property_type' => fake()->randomElement(array_keys(Listing::PROPERTY_TYPES)),
            'price' => $type === 'sale' ? fake()->numberBetween(150, 1200) * 10000 : fake()->numberBetween(15, 90) * 1000,
            'currency' => 'TRY',
            'city' => fake()->randomElement(['İstanbul', 'Ankara', 'İzmir', 'Bursa']),
            'district' => fake()->randomElement(['Kadıköy', 'Çankaya', 'Bornova', 'Nilüfer', 'Beşiktaş', 'Ataşehir']),
            'rooms' => fake()->randomElement(['1+1', '2+1', '3+1', '4+1']),
            'bathrooms' => fake()->numberBetween(1, 3),
            'area_gross' => fake()->numberBetween(60, 300),
            'area_net' => fake()->numberBetween(50, 250),
            'floor' => (string) fake()->numberBetween(1, 12),
            'building_age' => fake()->numberBetween(0, 25),
            'heating' => fake()->randomElement(['Kombi', 'Merkezi', 'Yerden Isıtma']),
            'furnished' => fake()->boolean(30),
            'description' => fake()->paragraphs(3, true),
            'features' => fake()->randomElements(['Asansör', 'Otopark', 'Güvenlik', 'Balkon', 'Deniz Manzarası', 'Site İçerisinde', 'Yüzme Havuzu', 'Spor Salonu', 'Ebeveyn Banyosu', 'Ankastre Mutfak'], 5),
            'is_active' => true,
            'is_featured' => fake()->boolean(30),
            'sort_order' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
