<?php

namespace App\Templates;

use Illuminate\Support\Str;

/**
 * Base class every sector template extends. A template owns its public design
 * (views, CSS, JS), its sector-specific content types, routes, demo content and
 * the default site settings that make the site ready to deliver.
 */
abstract class Template
{
    protected ?string $basePath = null;

    abstract public function key(): string;

    abstract public function name(): string;

    abstract public function description(): string;

    /**
     * Data passed to the "template::home" view.
     *
     * @return array<string, mixed>
     */
    abstract public function homeData(): array;

    public function withBasePath(string $basePath): static
    {
        $this->basePath = rtrim($basePath, '/');

        return $this;
    }

    public function path(string $append = ''): string
    {
        $base = ($this->basePath ?? rtrim(config('templates.path'), '/')).'/'.$this->key();

        return $append === '' ? $base : $base.'/'.ltrim($append, '/');
    }

    /**
     * Core features this template ships with. Disabled features have no routes,
     * no admin menu entry and no navigation link.
     *
     * @return array<string, bool>
     */
    public function features(): array
    {
        return [
            'services' => true,
            'products' => true,
            'blog' => true,
            'team' => true,
            'testimonials' => true,
            'faqs' => true,
            'partners' => true,
        ];
    }

    public function supports(string $feature): bool
    {
        return (bool) ($this->features()[$feature] ?? false);
    }

    /**
     * Settings written on first install (merged over the core defaults).
     *
     * @return array<string, array<string, string|null>> group => [key => value]
     */
    public function defaultSettings(): array
    {
        return [];
    }

    /**
     * Extra settings shown in the "Şablon" tab of Site Ayarları.
     *
     * @return array<string, array{label: string, type: string, help?: string, rules?: array<int, string>}>
     */
    public function settingFields(): array
    {
        return [];
    }

    /**
     * Admin sidebar entries for sector content.
     *
     * @return array<int, array{label: string, route: string, icon?: string, pattern?: string, permission?: string}>
     */
    public function adminMenu(): array
    {
        return [];
    }

    /**
     * Extra public navigation entries (inserted after the core links).
     *
     * @return array<int, array{label: string, route: string, pattern?: string}>
     */
    public function publicMenu(): array
    {
        return [];
    }

    /**
     * @return array<int, array{label: string, value: int|string, route?: string, icon?: string, sub?: string}>
     */
    public function dashboardStats(): array
    {
        return [];
    }

    /**
     * @return array<int, array{url: string, updated: \DateTimeInterface|null, priority?: string}>
     */
    public function sitemapEntries(): array
    {
        return [];
    }

    /**
     * Contact message types this template creates (key => label).
     *
     * @return array<string, string>
     */
    public function messageTypes(): array
    {
        return [];
    }

    /**
     * Vite entry points for the public site.
     *
     * @return array<int, string>
     */
    public function assets(): array
    {
        return [
            'templates/'.$this->key().'/css/app.css',
            'templates/'.$this->key().'/js/app.js',
        ];
    }

    public function routesFile(): string
    {
        return $this->path('routes.php');
    }

    public function viewsPath(): string
    {
        return $this->path('views');
    }

    public function migrationsPath(): string
    {
        return $this->path('database/migrations');
    }

    /** Fully qualified seeder class that creates demo content (null = none). */
    public function demoSeeder(): ?string
    {
        $class = $this->namespace().'\\Seeders\\DemoSeeder';

        return class_exists($class) ? $class : null;
    }

    public function namespace(): string
    {
        return 'Templates\\'.Str::studly($this->key());
    }
}
