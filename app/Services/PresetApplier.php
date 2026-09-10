<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class PresetApplier
{
    public function __construct(
        private Settings $settings,
        private ModuleRegistry $modules,
        private ThemeManager $themes,
    ) {}

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return config('presets', []);
    }

    public function exists(string $key): bool
    {
        return array_key_exists($key, $this->all());
    }

    public function apply(string $key): void
    {
        $preset = $this->all()[$key] ?? throw new \InvalidArgumentException("Bilinmeyen ön ayar: {$key}");

        $values = $preset['settings'] ?? [];
        $values['theme'] = $preset['theme'] ?? config('themes.default');
        $values['primary_color'] = $preset['primary_color'] ?? ($this->themes->config($values['theme'])['primary_color'] ?? '#1d4ed8');
        $values['font'] = null; // let the theme decide
        $values['active_preset'] = $key;

        // Presets that do not mention a toggle should restore the default (visible) state.
        foreach (['nav_show_services', 'nav_show_products', 'nav_show_blog', 'show_services', 'show_products', 'show_blog'] as $toggle) {
            $values[$toggle] = $values[$toggle] ?? '1';
        }

        $this->settings->setMany($values, 'preset');

        foreach ($this->modules->all() as $moduleKey => $module) {
            $this->modules->setActive($moduleKey, in_array($moduleKey, $preset['modules'] ?? [], true));
        }

        $this->themes->forget();
        Cache::flush();
    }
}
