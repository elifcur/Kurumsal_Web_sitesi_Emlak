<?php

use App\Services\Settings;
use App\Templates\Template;
use App\Templates\TemplateManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

if (! function_exists('setting')) {
    /**
     * Retrieve a site setting value.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return app(Settings::class)->get($key, $default);
    }
}

if (! function_exists('template')) {
    /**
     * The active sector template.
     */
    function template(): Template
    {
        return app(TemplateManager::class)->active();
    }
}

if (! function_exists('upload_url')) {
    /**
     * Resolve the public URL of an uploaded file path (or return the path if it is already absolute).
     */
    function upload_url(?string $path, ?string $fallback = null): ?string
    {
        if (! $path) {
            return $fallback;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return Storage::disk(config('cms.uploads.disk'))->url($path);
    }
}

if (! function_exists('phone_href')) {
    /**
     * Normalise a display phone number into a tel: href value.
     */
    function phone_href(?string $phone): string
    {
        return 'tel:'.preg_replace('/[^\d+]/', '', (string) $phone);
    }
}

if (! function_exists('organization_schema')) {
    /**
     * JSON-LD Organization schema built from site settings.
     */
    function organization_schema(): string
    {
        $schema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => setting('schema_type', 'Organization'),
            'name' => setting('site_name', config('app.name')),
            'url' => url('/'),
            'logo' => upload_url(setting('logo')),
            'telephone' => setting('phone'),
            'email' => setting('email'),
            'address' => setting('address') ? ['@type' => 'PostalAddress', 'streetAddress' => setting('address')] : null,
            'sameAs' => array_values(array_filter([setting('facebook'), setting('instagram'), setting('twitter'), setting('linkedin'), setting('youtube')])),
        ]);

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}

if (! function_exists('breadcrumb_schema')) {
    /**
     * JSON-LD BreadcrumbList schema.
     *
     * @param  array<int, array{label: string, url?: string}>  $breadcrumbs
     */
    function breadcrumb_schema(array $breadcrumbs): string
    {
        $items = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Ana Sayfa', 'item' => route('home')]];

        foreach (array_values($breadcrumbs) as $index => $crumb) {
            $items[] = array_filter(['@type' => 'ListItem', 'position' => $index + 2, 'name' => $crumb['label'], 'item' => $crumb['url'] ?? null]);
        }

        return json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}

if (! function_exists('emphasize')) {
    /**
     * Escape text and turn *word* markers into <em> for editorial-style headings.
     */
    function emphasize(?string $text): HtmlString
    {
        $escaped = e((string) $text);

        return new HtmlString(preg_replace('/\*([^*]+)\*/u', '<em>$1</em>', $escaped));
    }
}

if (! function_exists('site_stats')) {
    /**
     * Parse the "value|label;value|label" stats setting.
     *
     * @return Collection<int, array{value: string, label: string}>
     */
    function site_stats(string $key = 'stats'): Collection
    {
        return collect(explode(';', (string) setting($key, '')))
            ->filter()
            ->map(function (string $item): array {
                [$value, $label] = array_pad(explode('|', $item, 2), 2, '');

                return ['value' => trim($value), 'label' => trim($label)];
            })
            ->filter(fn (array $stat) => $stat['value'] !== '')
            ->values();
    }
}
