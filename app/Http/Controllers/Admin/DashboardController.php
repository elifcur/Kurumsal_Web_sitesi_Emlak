<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Templates\Template;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(Template $template): View
    {
        $stats = [
            ['label' => 'Sayfalar', 'value' => Page::query()->count(), 'sub' => Page::query()->published()->count().' yayında', 'icon' => 'document', 'route' => 'admin.pages.index', 'permission' => 'manage-pages'],
        ];

        if ($template->supports('services')) {
            $stats[] = ['label' => setting('services_label', 'Hizmetler'), 'value' => Service::query()->count(), 'sub' => Service::query()->active()->count().' aktif', 'icon' => 'briefcase', 'route' => 'admin.services.index', 'permission' => 'manage-services'];
        }

        if ($template->supports('products')) {
            $stats[] = ['label' => setting('products_label', 'Ürünler'), 'value' => Product::query()->count(), 'sub' => Product::query()->active()->count().' aktif', 'icon' => 'box', 'route' => 'admin.products.index', 'permission' => 'manage-products'];
        }

        foreach ($template->dashboardStats() as $stat) {
            $stats[] = $stat + ['icon' => 'puzzle', 'permission' => 'manage-sector', 'sub' => $template->name()];
        }

        if ($template->supports('blog')) {
            $stats[] = ['label' => 'Blog Yazıları', 'value' => Post::query()->count(), 'sub' => Post::query()->published()->count().' yayında', 'icon' => 'newspaper', 'route' => 'admin.posts.index', 'permission' => 'manage-posts'];
        }

        $stats[] = ['label' => 'Mesajlar', 'value' => ContactMessage::query()->count(), 'sub' => ContactMessage::query()->unread()->count().' okunmamış', 'icon' => 'inbox', 'route' => 'admin.messages.index', 'permission' => 'manage-messages'];
        $stats[] = ['label' => 'Medya', 'value' => Media::query()->count(), 'sub' => 'dosya', 'icon' => 'photo', 'route' => 'admin.media.index', 'permission' => 'manage-media'];

        return view('admin.dashboard.index', [
            'stats' => array_filter($stats, fn (array $stat) => auth()->user()->can($stat['permission'])),
            'recentMessages' => ContactMessage::query()->latest()->limit(5)->get(),
            'recentPosts' => $template->supports('blog') ? Post::query()->with('author')->latest()->limit(5)->get() : collect(),
            'recentPages' => Page::query()->latest('updated_at')->limit(5)->get(),
            'userCount' => User::query()->count(),
        ]);
    }
}
