<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Services\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Core defaults, overridden by the active template's defaultSettings().
     *
     * @return array<string, array<string, string|null>>
     */
    public static function defaults(): array
    {
        return [
            'general' => [
                'site_name' => 'Kurumsal Firma',
                'site_tagline' => 'Sektörünüzde güvenilir çözüm ortağınız',
                'logo' => null,
                'favicon' => null,
                'footer_text' => 'Tüm hakları saklıdır.',
                'primary_color' => '#1d4ed8',
                'services_label' => 'Hizmetler',
                'products_label' => 'Ürünler',
                'blog_label' => 'Blog',
                'nav_show_services' => '1',
                'nav_show_products' => '1',
                'nav_show_blog' => '1',
            ],
            'contact' => [
                'phone' => '+90 (212) 000 00 00',
                'whatsapp' => '905000000000',
                'email' => 'info@example.com',
                'address' => 'Örnek Mah. Örnek Cad. No:1, İstanbul',
                'working_hours' => 'Pzt - Cum: 09:00 - 18:00',
                'map_embed' => null,
                'contact_recipient' => null,
            ],
            'social' => [
                'facebook' => null,
                'instagram' => null,
                'twitter' => null,
                'linkedin' => null,
                'youtube' => null,
            ],
            'seo' => [
                'meta_title' => 'Kurumsal Firma | Ana Sayfa',
                'meta_description' => 'Kurumsal Firma olarak müşterilerimize kaliteli ve güvenilir hizmet sunuyoruz.',
                'og_image' => null,
                'google_analytics_id' => null,
                'google_site_verification' => null,
                'head_scripts' => null,
            ],
            'home' => [
                'hero_title' => 'İşinizi Büyütecek Kurumsal Çözümler',
                'hero_subtitle' => 'Deneyimli ekibimiz ve modern altyapımızla ihtiyaçlarınıza özel hizmet sunuyoruz.',
                'hero_image' => null,
                'hero_button_text' => 'Hizmetlerimiz',
                'hero_button_url' => '/hizmetler',
                'hero_button2_text' => 'İletişime Geçin',
                'hero_button2_url' => '/iletisim',
                'about_title' => 'Hakkımızda',
                'about_text' => 'Yılların deneyimi ile sektörümüzde öncü konumdayız. Müşteri memnuniyetini her zaman ön planda tutuyoruz.',
                'about_image' => null,
                'services_intro' => 'İhtiyaçlarınıza özel, uçtan uca çözümler sunuyoruz.',
                'products_intro' => null,
                'blog_intro' => null,
                'cta_title' => 'Projeniz için hemen teklif alın',
                'cta_text' => 'Uzman ekibimiz size en kısa sürede dönüş yapacaktır.',
                'cta_button_text' => 'Teklif Al',
                'cta_button_url' => '/iletisim',
                'show_services' => '1',
                'show_products' => '1',
                'show_blog' => '1',
                'stats' => '15+|Yıllık Deneyim;500+|Mutlu Müşteri;1000+|Tamamlanan Proje;24/7|Destek',
            ],
        ];
    }

    public function run(bool $overwrite = false): void
    {
        $defaults = self::defaults();

        foreach (template()->defaultSettings() as $group => $values) {
            $defaults[$group] = array_merge($defaults[$group] ?? [], $values);
        }

        foreach ($defaults as $group => $values) {
            foreach ($values as $key => $value) {
                $existing = Setting::query()->where('key', $key)->first();

                if ($existing === null) {
                    Setting::query()->create(['key' => $key, 'value' => $value, 'group' => $group]);
                } elseif ($overwrite) {
                    $existing->update(['value' => $value, 'group' => $group]);
                }
            }
        }

        app(Settings::class)->flush();
    }
}
