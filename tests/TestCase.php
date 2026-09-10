<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Views reference template assets via @vite; tests must not depend on public/build.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    protected function superAdmin(): User
    {
        return User::factory()->superAdmin()->create();
    }

    protected function admin(): User
    {
        return User::factory()->admin()->create();
    }

    protected function editor(): User
    {
        return User::factory()->create();
    }

    /**
     * Skip a test when the active template does not ship the given core feature.
     */
    protected function requiresFeature(string $feature): void
    {
        if (! template()->supports($feature)) {
            $this->markTestSkipped("Aktif şablon ({$this->activeTemplateKey()}) '{$feature}' özelliğini içermiyor.");
        }
    }

    protected function activeTemplateKey(): string
    {
        return template()->key();
    }
}
