<?php

namespace App\Services;

use Illuminate\Contracts\View\Factory as ViewFactory;

class ThemeManager
{
    private ?string $key = null;

    public function __construct(private Settings $settings) {}

    /** Active theme key (falls back to the default when the stored value is unknown). */
    public function key(): string
    {
        if ($this->key !== null) {
            return $this->key;
        }

        $stored = (string) $this->settings->get('theme', config('themes.default'));

        return $this->key = $this->exists($stored) ? $stored : config('themes.default');
    }

    public function exists(string $key): bool
    {
        return array_key_exists($key, config('themes.available', []));
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return config('themes.available', []);
    }

    /**
     * @return array<string, mixed>
     */
    public function config(?string $key = null): array
    {
        return config('themes.available.'.($key ?? $this->key()), []);
    }

    /** Font alias in effect: explicit setting wins, otherwise the theme default. */
    public function fontAlias(): string
    {
        $fonts = config('themes.fonts', []);
        $stored = (string) $this->settings->get('font', '');

        if ($stored !== '' && array_key_exists($stored, $fonts)) {
            return $stored;
        }

        return $this->config()['font'] ?? array_key_first($fonts);
    }

    public function fontFamily(): string
    {
        return config('themes.fonts.'.$this->fontAlias(), 'Inter');
    }

    public function primaryColor(): string
    {
        return (string) $this->settings->get('primary_color', $this->config()['primary_color'] ?? '#1d4ed8');
    }

    public function viewPath(?string $key = null): string
    {
        return resource_path('views/themes/'.($key ?? $this->key()));
    }

    /** Registers the active theme's view directory as the first lookup location. */
    public function register(ViewFactory $views): void
    {
        $path = $this->viewPath();

        if (is_dir($path)) {
            $views->getFinder()->prependLocation($path);
        }
    }

    public function forget(): void
    {
        $this->key = null;
    }
}
