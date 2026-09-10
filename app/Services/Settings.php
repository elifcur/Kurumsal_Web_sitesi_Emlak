<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Settings
{
    private const CACHE_KEY = 'cms.settings';

    /** @var array<string, string|null>|null */
    private ?array $items = null;

    public function get(string $key, mixed $default = null): mixed
    {
        $items = $this->all();

        if (! array_key_exists($key, $items) || $items[$key] === null || $items[$key] === '') {
            return $default;
        }

        return $items[$key];
    }

    /**
     * @return array<string, string|null>
     */
    public function all(): array
    {
        if ($this->items !== null) {
            return $this->items;
        }

        try {
            if (! Schema::hasTable('settings')) {
                return $this->items = [];
            }

            $this->items = Cache::rememberForever(self::CACHE_KEY, function (): array {
                return Setting::query()->pluck('value', 'key')->all();
            });
        } catch (\Throwable) {
            $this->items = [];
        }

        return $this->items;
    }

    public function set(string $key, mixed $value, string $group = 'general'): void
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value === null ? null : (string) $value, 'group' => $group]
        );

        $this->flush();
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function setMany(array $values, string $group = 'general'): void
    {
        foreach ($values as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value === null ? null : (string) $value, 'group' => $group]
            );
        }

        $this->flush();
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
        $this->items = null;
    }
}
