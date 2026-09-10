<?php

namespace Templates\RealEstate;

use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Templates\Template as BaseTemplate;
use Templates\RealEstate\Models\Listing;

class Template extends BaseTemplate
{
    public function key(): string
    {
        return 'real-estate';
    }

    public function name(): string
    {
        return 'Emlak';
    }

    public function description(): string
    {
        return 'Emlak ofisleri ve gayrimenkul danışmanları için: satılık/kiralık ilan portföyü, filtreleme, ilan talep formu, danışman ekibi.';
    }

    public function features(): array
    {
        return [
            'services' => true,
            'products' => false,
            'blog' => true,
            'team' => true,
            'testimonials' => true,
            'faqs' => true,
            'partners' => true,
        ];
    }

    public function defaultSettings(): array
    {
        return [
            'general' => [
                'site_name' => 'Prestij Emlak',
                'site_tagline' => 'Hayallerinizdeki Yaşam',
                'primary_color' => '#1f3d2e',
                'services_label' => 'Hizmetlerimiz',
                'blog_label' => 'Blog',
            ],
            'seo' => [
                'meta_title' => 'Prestij Emlak | Satılık ve Kiralık İlanlar',
                'meta_description' => 'İstanbul ve çevresinde satılık, kiralık daire, villa, ofis ve arsa ilanları. Uzman danışman kadromuzla güvenli gayrimenkul alım-satım.',
            ],
            'home' => [
                'hero_title' => 'Size en uygun gayrimenkulü *birlikte* bulalım',
                'hero_subtitle' => 'Satılık ve kiralık konut, villa, arsa ve ticari gayrimenkul seçenekleriyle aradığınız evi, ofisi ya da yatırımı şimdi keşfedin.',
                'hero_button_text' => 'İlanları İncele',
                'hero_button_url' => '/ilanlar',
                'hero_button2_text' => 'Profesyonel Danışmanlık',
                'hero_button2_url' => '/hizmetler',
                'about_title' => 'Neden Prestij Emlak?',
                'about_text' => "15 yılı aşkın süredir gayrimenkul sektöründe güvenilir çözüm ortağınızız. Portföyümüzdeki her ilanı yerinde inceliyor, tapu ve kredi süreçlerinde yanınızda oluyoruz.\nAmacımız sadece bir mülk satmak değil, sizin için doğru yatırımı bulmaktır.",
                'services_intro' => 'Alım, satım, kiralama ve değerlemede uçtan uca danışmanlık.',
                'cta_title' => 'Mülkünüzü satmak veya kiraya vermek mi istiyorsunuz?',
                'cta_text' => 'Ücretsiz ekspertiz için hemen bize ulaşın, portföyümüze ekleyelim.',
                'cta_button_text' => 'Ücretsiz Değerleme İste',
                'cta_button_url' => '/iletisim',
                'stats' => '15+|Yıllık Deneyim;1200+|Satılan Mülk;350+|Aktif İlan;%98|Müşteri Memnuniyeti',
            ],
            'template' => [
                'listings_label' => 'İlanlar',
                'listings_home_title' => 'Sizin İçin Seçtiklerimiz',
                'listings_home_intro' => null,
                'team_home_title' => 'Uzman Danışmanlarımız',
                'hero_card_title' => 'Güvenli Yatırım',
                'hero_card_text' => 'Doğru Adres',
                'about_quote' => 'Amacımız sadece bir mülk satmak değil, sizin için doğru yatırımı bulmaktır.',
                'inquiry_success' => 'Talebiniz alındı. Danışmanımız en kısa sürede sizinle iletişime geçecek.',
            ],
        ];
    }

    public function settingFields(): array
    {
        return [
            'listings_label' => ['label' => 'İlanlar menü etiketi', 'type' => 'text', 'rules' => ['max:50']],
            'listings_home_title' => ['label' => 'Ana sayfa ilan bölümü başlığı', 'type' => 'text', 'rules' => ['max:120']],
            'listings_home_intro' => ['label' => 'Ana sayfa ilan bölümü açıklaması', 'type' => 'text', 'rules' => ['max:255']],
            'team_home_title' => ['label' => 'Ana sayfa danışmanlar başlığı', 'type' => 'text', 'rules' => ['max:120']],
            'hero_card_title' => ['label' => 'Hero bilgi kartı 1. satır', 'type' => 'text', 'help' => 'Boş bırakılırsa kart gösterilmez.', 'rules' => ['max:60']],
            'hero_card_text' => ['label' => 'Hero bilgi kartı 2. satır', 'type' => 'text', 'rules' => ['max:60']],
            'about_quote' => ['label' => 'İstatistik şeridi alıntısı', 'type' => 'text', 'rules' => ['max:255']],
            'inquiry_success' => ['label' => 'İlan talep formu başarı mesajı', 'type' => 'text', 'rules' => ['max:255']],
        ];
    }

    public function adminMenu(): array
    {
        return [
            ['label' => 'İlanlar', 'route' => 'admin.listings.index', 'pattern' => 'admin.listings.*', 'icon' => 'home', 'permission' => 'manage-sector'],
        ];
    }

    public function publicMenu(): array
    {
        return [
            ['label' => setting('listings_label', 'İlanlar'), 'route' => 'listings.index', 'pattern' => 'ilanlar*'],
        ];
    }

    public function dashboardStats(): array
    {
        return [
            ['label' => 'İlanlar', 'value' => Listing::query()->count(), 'sub' => Listing::query()->active()->count().' yayında', 'route' => 'admin.listings.index', 'icon' => 'home'],
        ];
    }

    public function sitemapEntries(): array
    {
        $entries = [['url' => route('listings.index'), 'updated' => null, 'priority' => '0.9']];

        foreach (Listing::query()->active()->get(['slug', 'updated_at']) as $listing) {
            $entries[] = ['url' => route('listings.show', $listing->slug), 'updated' => $listing->updated_at, 'priority' => '0.8'];
        }

        return $entries;
    }

    public function messageTypes(): array
    {
        return ['listing' => 'İlan Talebi', 'valuation' => 'Değerleme Talebi'];
    }

    public function homeData(): array
    {
        return [
            'featuredListings' => Listing::query()->active()->ordered()->limit(4)->get(),
            'listingCounts' => [
                'sale' => Listing::query()->active()->where('listing_type', 'sale')->count(),
                'rent' => Listing::query()->active()->where('listing_type', 'rent')->count(),
            ],
            'cities' => Listing::query()->active()->whereNotNull('city')->distinct()->orderBy('city')->pluck('city'),
            'services' => setting('show_services', '1') === '1' ? Service::query()->active()->ordered()->limit(4)->get() : collect(),
            'team' => TeamMember::query()->active()->where('is_featured', true)->ordered()->limit(4)->get(),
            'testimonials' => Testimonial::query()->active()->ordered()->limit(3)->get(),
            'faqs' => Faq::query()->active()->ordered()->limit(6)->get(),
            'partners' => Partner::query()->active()->ordered()->get(),
            'posts' => setting('show_blog', '1') === '1' ? Post::query()->with('category')->published()->latestPublished()->limit(3)->get() : collect(),
            'stats' => site_stats(),
        ];
    }
}
