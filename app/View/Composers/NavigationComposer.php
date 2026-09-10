<?php

namespace App\View\Composers;

use App\Models\Page;
use App\Models\Product;
use App\Models\Service;
use App\Services\Settings;
use App\Templates\Template;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Shares navigation data with every template layout ("template::layouts.*").
 */
class NavigationComposer
{
    public function __construct(
        private Settings $settings,
        private Template $template,
    ) {}

    public function compose(View $view): void
    {
        $view->with('siteNav', $this->navigation());
        $view->with('footerPages', $this->footerPages());
        $view->with('footerServices', $this->template->supports('services')
            ? Cache::remember('nav.footer_services', 600, fn () => Service::query()->active()->ordered()->limit(6)->get(['title', 'slug'])->map->only(['title', 'slug'])->all())
            : []);
    }

    /**
     * @return array<int, array{label: string, url: string, active: bool}>
     */
    private function navigation(): array
    {
        $items = [['label' => 'Ana Sayfa', 'url' => route('home'), 'pattern' => '/']];

        $menuPages = Cache::remember('nav.menu_pages', 600, fn () => Page::query()->published()->where('show_in_menu', true)->ordered()->get(['title', 'slug'])->map->only(['title', 'slug'])->all());

        foreach ($menuPages as $page) {
            $items[] = ['label' => $page['title'], 'url' => route('page.show', $page['slug']), 'pattern' => $page['slug']];
        }

        if ($this->template->supports('services') && $this->settings->get('nav_show_services', '1') === '1' && Cache::remember('nav.has_services', 600, fn () => Service::query()->active()->exists())) {
            $items[] = ['label' => $this->settings->get('services_label', 'Hizmetler'), 'url' => route('services.index'), 'pattern' => 'hizmetler*'];
        }

        if ($this->template->supports('products') && $this->settings->get('nav_show_products', '1') === '1' && Cache::remember('nav.has_products', 600, fn () => Product::query()->active()->exists())) {
            $items[] = ['label' => $this->settings->get('products_label', 'Ürünler'), 'url' => route('products.index'), 'pattern' => 'urunler*'];
        }

        foreach ($this->template->publicMenu() as $entry) {
            $items[] = ['label' => $entry['label'], 'url' => route($entry['route']), 'pattern' => $entry['pattern'] ?? ltrim((string) parse_url(route($entry['route']), PHP_URL_PATH), '/').'*'];
        }

        if ($this->template->supports('blog') && $this->settings->get('nav_show_blog', '1') === '1') {
            $items[] = ['label' => $this->settings->get('blog_label', 'Blog'), 'url' => route('blog.index'), 'pattern' => 'blog*'];
        }

        $items[] = ['label' => 'İletişim', 'url' => route('contact.show'), 'pattern' => 'iletisim'];

        return array_map(function (array $item): array {
            $item['active'] = request()->is($item['pattern']);
            unset($item['pattern']);

            return $item;
        }, $items);
    }

    /**
     * @return array<int, array{title: string, slug: string}>
     */
    private function footerPages(): array
    {
        return Cache::remember('nav.footer_pages', 600, fn () => Page::query()->published()->where('show_in_footer', true)->ordered()->get(['title', 'slug'])->map->only(['title', 'slug'])->all());
    }
}
