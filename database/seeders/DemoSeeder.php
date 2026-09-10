<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Loads the active template's demo content (sector records, sample services,
 * posts, team, testimonials...). Each template ships its own DemoSeeder.
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $seeder = template()->demoSeeder();

        if ($seeder === null) {
            $this->command?->warn('Aktif şablonun demo seeder\'ı yok: '.template()->key());

            return;
        }

        $this->call($seeder);
    }
}
