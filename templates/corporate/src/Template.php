<?php

namespace Templates\Corporate;

use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Templates\Template as BaseTemplate;

/**
 * Sector-neutral corporate site. Ships no sector models of its own and uses
 * the core fallback views for every page except the home page, so it doubles
 * as the reference for what a template must provide at minimum.
 */
class Template extends BaseTemplate
{
    public function key(): string
    {
        return 'corporate';
    }

    public function name(): string
    {
        return 'Kurumsal';
    }

    public function description(): string
    {
        return 'Sektörden bağımsız kurumsal site: hizmetler, ürün kataloğu, blog, ekip, referanslar ve iletişim.';
    }

    public function defaultSettings(): array
    {
        return [
            'general' => [
                'site_name' => 'Kurumsal Firma',
                'site_tagline' => 'Güvenilir Çözüm Ortağınız',
                'primary_color' => '#1d4ed8',
            ],
            'home' => [
                'hero_title' => 'İşinizi büyütecek *kurumsal* çözümler',
                'hero_subtitle' => 'Deneyimli ekibimiz ve modern altyapımızla ihtiyaçlarınıza özel hizmet ve ürünler sunuyoruz.',
                'hero_button_text' => 'Hizmetlerimiz',
                'hero_button_url' => '/hizmetler',
                'hero_button2_text' => 'Teklif Alın',
                'hero_button2_url' => '/iletisim',
                'about_title' => 'Neden *biz*?',
                'about_text' => "Yılların deneyimiyle sektörümüzde öncü konumdayız. Müşteri memnuniyetini her zaman ön planda tutuyor, her projeye özel çözümler üretiyoruz.\nKaliteli hizmet, şeffaf iletişim ve zamanında teslimat ilkelerimizle çalışıyoruz.",
                'stats' => '15+|Yıllık Deneyim;500+|Mutlu Müşteri;1000+|Tamamlanan Proje;7/24|Destek',
            ],
        ];
    }

    public function settingFields(): array
    {
        return [
            'about_points' => ['label' => 'Hakkımızda madde listesi', 'type' => 'textarea', 'help' => 'Her satıra bir madde. Boş bırakılırsa liste gösterilmez.', 'rules' => ['max:500']],
            'about_quote' => ['label' => 'İstatistik şeridi alıntısı', 'type' => 'text', 'rules' => ['max:255']],
        ];
    }

    public function homeData(): array
    {
        return [
            'services' => setting('show_services', '1') === '1' ? Service::query()->active()->ordered()->limit(6)->get() : collect(),
            'products' => setting('show_products', '1') === '1' ? Product::query()->with('category')->active()->where('is_featured', true)->ordered()->limit(4)->get() : collect(),
            'team' => TeamMember::query()->active()->where('is_featured', true)->ordered()->limit(4)->get(),
            'testimonials' => Testimonial::query()->active()->ordered()->limit(3)->get(),
            'faqs' => Faq::query()->active()->ordered()->limit(6)->get(),
            'partners' => Partner::query()->active()->ordered()->get(),
            'posts' => setting('show_blog', '1') === '1' ? Post::query()->with('category')->published()->latestPublished()->limit(3)->get() : collect(),
            'stats' => site_stats(),
            'aboutPoints' => collect(preg_split('/\r\n|\r|\n/', (string) setting('about_points', '')))->map(trim(...))->filter()->values(),
        ];
    }
}
