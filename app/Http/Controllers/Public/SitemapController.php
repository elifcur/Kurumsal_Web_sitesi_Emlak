<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Templates\Template;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(Template $template): Response
    {
        $entries = [
            ['url' => route('home'), 'updated' => null, 'priority' => '1.0'],
            ['url' => route('contact.show'), 'updated' => null, 'priority' => '0.6'],
        ];

        Page::query()->published()->get(['slug', 'updated_at'])
            ->each(function (Page $page) use (&$entries): void {
                $entries[] = ['url' => route('page.show', $page->slug), 'updated' => $page->updated_at, 'priority' => '0.7'];
            });

        if ($template->supports('services')) {
            $entries[] = ['url' => route('services.index'), 'updated' => null, 'priority' => '0.8'];

            Service::query()->active()->get(['slug', 'updated_at'])
                ->each(function (Service $service) use (&$entries): void {
                    $entries[] = ['url' => route('services.show', $service->slug), 'updated' => $service->updated_at, 'priority' => '0.7'];
                });
        }

        if ($template->supports('products')) {
            $entries[] = ['url' => route('products.index'), 'updated' => null, 'priority' => '0.8'];

            Product::query()->active()->get(['slug', 'updated_at'])
                ->each(function (Product $product) use (&$entries): void {
                    $entries[] = ['url' => route('products.show', $product->slug), 'updated' => $product->updated_at, 'priority' => '0.6'];
                });
        }

        if ($template->supports('blog')) {
            $entries[] = ['url' => route('blog.index'), 'updated' => null, 'priority' => '0.7'];

            Post::query()->published()->get(['slug', 'updated_at'])
                ->each(function (Post $post) use (&$entries): void {
                    $entries[] = ['url' => route('blog.show', $post->slug), 'updated' => $post->updated_at, 'priority' => '0.6'];
                });
        }

        foreach ($template->sitemapEntries() as $entry) {
            $entries[] = $entry + ['priority' => '0.6'];
        }

        return response()->view('sitemap', ['entries' => $entries])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /admin/',
            'Allow: /',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines))->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
