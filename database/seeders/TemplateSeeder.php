<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Re-applies the active template's defaults after switching templates with
 * "template:use --seed": settings are overwritten, demo content is added.
 */
class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SettingsSeeder::class, parameters: ['overwrite' => true]);
        $this->call(PageSeeder::class);
        $this->call(DemoSeeder::class);
    }
}
