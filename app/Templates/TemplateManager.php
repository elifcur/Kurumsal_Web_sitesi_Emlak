<?php

namespace App\Templates;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

class TemplateManager
{
    private ?Template $active = null;

    public function __construct(
        private readonly string $basePath,
        private readonly string $activeKey,
    ) {}

    /**
     * Autoloads Templates\{Studly}\... classes from templates/{kebab}/src.
     * Registered here (instead of composer.json) so templates can be added or
     * pruned without touching the autoloader configuration.
     */
    public static function registerAutoloader(string $basePath): void
    {
        spl_autoload_register(function (string $class) use ($basePath): void {
            if (! str_starts_with($class, 'Templates\\')) {
                return;
            }

            $parts = explode('\\', $class);

            if (count($parts) < 3) {
                return;
            }

            $file = $basePath.'/'.Str::kebab($parts[1]).'/src/'.implode('/', array_slice($parts, 2)).'.php';

            if (is_file($file)) {
                require $file;
            }
        });
    }

    public function activeKey(): string
    {
        return $this->activeKey;
    }

    public function active(): Template
    {
        return $this->active ??= $this->make($this->activeKey);
    }

    public function has(string $key): bool
    {
        return is_file($this->basePath.'/'.$key.'/src/Template.php');
    }

    public function make(string $key): Template
    {
        if (! preg_match('/^[a-z0-9\-]+$/', $key) || ! $this->has($key)) {
            throw new RuntimeException("Şablon bulunamadı: [{$key}]. templates/ dizinini ve APP_TEMPLATE değerini kontrol edin.");
        }

        $class = 'Templates\\'.Str::studly($key).'\\Template';

        if (! class_exists($class)) {
            throw new RuntimeException("Şablon sınıfı bulunamadı: {$class}");
        }

        return (new $class)->withBasePath($this->basePath);
    }

    /**
     * @return array<int, string>
     */
    public function keys(): array
    {
        $keys = [];

        foreach (glob($this->basePath.'/*/src/Template.php') ?: [] as $file) {
            $keys[] = basename(dirname($file, 2));
        }

        sort($keys);

        return $keys;
    }

    /**
     * @return Collection<int, Template>
     */
    public function all(): Collection
    {
        return collect($this->keys())->map(fn (string $key) => $this->make($key));
    }

    public function basePath(): string
    {
        return $this->basePath;
    }
}
