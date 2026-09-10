<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Settings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Setting definitions grouped by tab: key => [label, type, help, rules].
     *
     * @return array<string, array{label: string, fields: array<string, array{label: string, type: string, help?: string, rules?: array<int, string>}>}>
     */
    public static function groups(): array
    {
        $template = template();

        $groups = [
            'general' => [
                'label' => 'Genel',
                'fields' => [
                    'site_name' => ['label' => 'Site Adı', 'type' => 'text', 'rules' => ['required', 'max:100']],
                    'site_tagline' => ['label' => 'Slogan', 'type' => 'text', 'rules' => ['max:160']],
                    'logo' => ['label' => 'Logo', 'type' => 'image'],
                    'favicon' => ['label' => 'Favicon', 'type' => 'image', 'help' => 'PNG, 64x64 veya 32x32 önerilir.'],
                    'primary_color' => ['label' => 'Ana Renk', 'type' => 'color', 'help' => 'Sitenin marka rengi.', 'rules' => ['regex:/^#[0-9a-fA-F]{6}$/']],
                    'footer_text' => ['label' => 'Alt Bilgi Metni', 'type' => 'text', 'rules' => ['max:255']],
                    'services_label' => ['label' => '"Hizmetler" Menü Etiketi', 'type' => 'text', 'help' => 'Örn. Tedaviler, Araç Hizmetleri', 'rules' => ['max:50']],
                    'products_label' => ['label' => '"Ürünler" Menü Etiketi', 'type' => 'text', 'help' => 'Örn. Çözümler, Kitaplar', 'rules' => ['max:50']],
                    'blog_label' => ['label' => '"Blog" Menü Etiketi', 'type' => 'text', 'help' => 'Örn. Haberler', 'rules' => ['max:50']],
                    'nav_show_services' => ['label' => 'Menüde Hizmetler bağlantısı', 'type' => 'boolean'],
                    'nav_show_products' => ['label' => 'Menüde Ürünler bağlantısı', 'type' => 'boolean'],
                    'nav_show_blog' => ['label' => 'Menüde Blog bağlantısı', 'type' => 'boolean'],
                ],
            ],
            'contact' => [
                'label' => 'İletişim',
                'fields' => [
                    'phone' => ['label' => 'Telefon', 'type' => 'text', 'rules' => ['max:40']],
                    'whatsapp' => ['label' => 'WhatsApp Numarası', 'type' => 'text', 'help' => 'Ülke koduyla, boşluksuz: 905xxxxxxxxx', 'rules' => ['max:20', 'regex:/^[0-9+ ]*$/']],
                    'email' => ['label' => 'E-posta', 'type' => 'email', 'rules' => ['nullable', 'email', 'max:190']],
                    'contact_recipient' => ['label' => 'Form Bildirim E-postası', 'type' => 'email', 'help' => 'İletişim formu mesajları bu adrese gönderilir. Boşsa yukarıdaki e-posta kullanılır.', 'rules' => ['nullable', 'email', 'max:190']],
                    'address' => ['label' => 'Adres', 'type' => 'textarea', 'rules' => ['max:500']],
                    'working_hours' => ['label' => 'Çalışma Saatleri', 'type' => 'text', 'rules' => ['max:100']],
                    'map_embed' => ['label' => 'Google Harita Embed URL', 'type' => 'text', 'help' => 'Google Maps > Paylaş > Harita yerleştir > iframe içindeki src adresi.', 'rules' => ['nullable', 'url', 'max:1000', 'regex:#^https://www\.google\.com/maps/embed#']],
                ],
            ],
            'social' => [
                'label' => 'Sosyal Medya',
                'fields' => [
                    'facebook' => ['label' => 'Facebook', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
                    'instagram' => ['label' => 'Instagram', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
                    'twitter' => ['label' => 'X (Twitter)', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
                    'linkedin' => ['label' => 'LinkedIn', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
                    'youtube' => ['label' => 'YouTube', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
                ],
            ],
            'home' => [
                'label' => 'Ana Sayfa',
                'fields' => [
                    'hero_title' => ['label' => 'Hero Başlık', 'type' => 'text', 'rules' => ['max:160']],
                    'hero_subtitle' => ['label' => 'Hero Alt Metin', 'type' => 'textarea', 'rules' => ['max:400']],
                    'hero_image' => ['label' => 'Hero Arka Plan Görseli', 'type' => 'image', 'help' => '1920x1080 önerilir.'],
                    'hero_button_text' => ['label' => '1. Buton Metni', 'type' => 'text', 'rules' => ['max:40']],
                    'hero_button_url' => ['label' => '1. Buton Bağlantısı', 'type' => 'text', 'rules' => ['max:255']],
                    'hero_button2_text' => ['label' => '2. Buton Metni', 'type' => 'text', 'rules' => ['max:40']],
                    'hero_button2_url' => ['label' => '2. Buton Bağlantısı', 'type' => 'text', 'rules' => ['max:255']],
                    'stats' => ['label' => 'İstatistikler', 'type' => 'textarea', 'help' => 'Biçim: değer|etiket; değer|etiket  (örn. 15+|Yıllık Deneyim;500+|Mutlu Müşteri)', 'rules' => ['max:500']],
                    'about_title' => ['label' => 'Hakkımızda Başlığı', 'type' => 'text', 'rules' => ['max:120']],
                    'about_text' => ['label' => 'Hakkımızda Metni', 'type' => 'textarea', 'rules' => ['max:2000']],
                    'about_image' => ['label' => 'Hakkımızda Görseli', 'type' => 'image'],
                    'services_intro' => ['label' => 'Hizmetler Bölümü Açıklaması', 'type' => 'text', 'rules' => ['max:255']],
                    'products_intro' => ['label' => 'Ürünler Sayfası Açıklaması', 'type' => 'text', 'rules' => ['max:255']],
                    'blog_intro' => ['label' => 'Blog Sayfası Açıklaması', 'type' => 'text', 'rules' => ['max:255']],
                    'cta_title' => ['label' => 'CTA Başlık', 'type' => 'text', 'rules' => ['max:160']],
                    'cta_text' => ['label' => 'CTA Metin', 'type' => 'text', 'rules' => ['max:255']],
                    'cta_button_text' => ['label' => 'CTA Buton Metni', 'type' => 'text', 'rules' => ['max:40']],
                    'cta_button_url' => ['label' => 'CTA Buton Bağlantısı', 'type' => 'text', 'rules' => ['max:255']],
                    'show_services' => ['label' => 'Ana sayfada hizmetleri göster', 'type' => 'boolean'],
                    'show_products' => ['label' => 'Ana sayfada öne çıkan ürünleri göster', 'type' => 'boolean'],
                    'show_blog' => ['label' => 'Ana sayfada son yazıları göster', 'type' => 'boolean'],
                ],
            ],
            'seo' => [
                'label' => 'SEO & Entegrasyon',
                'fields' => [
                    'meta_title' => ['label' => 'Varsayılan SEO Başlığı', 'type' => 'text', 'rules' => ['max:255']],
                    'meta_description' => ['label' => 'Varsayılan SEO Açıklaması', 'type' => 'textarea', 'rules' => ['max:320']],
                    'og_image' => ['label' => 'Varsayılan Paylaşım Görseli (OG)', 'type' => 'image', 'help' => '1200x630 önerilir.'],
                    'google_analytics_id' => ['label' => 'Google Analytics ID', 'type' => 'text', 'help' => 'Örn. G-XXXXXXXXXX', 'rules' => ['nullable', 'regex:/^(G|UA|GT|AW)-[A-Z0-9\-]+$/i', 'max:30']],
                    'google_site_verification' => ['label' => 'Google Site Doğrulama Kodu', 'type' => 'text', 'rules' => ['max:120', 'regex:/^[A-Za-z0-9_\-]*$/']],
                    'head_scripts' => ['label' => 'Ek <head> Kodları', 'type' => 'code', 'help' => 'Örn. Google Tag Manager, Meta Pixel. Yalnızca güvenilir kodlar ekleyin.', 'rules' => ['max:5000']],
                ],
            ],
        ];

        if ($template->settingFields() !== []) {
            $groups['template'] = ['label' => $template->name().' Ayarları', 'fields' => $template->settingFields()];
        }

        $hidden = [];

        if (! $template->supports('services')) {
            $hidden = [...$hidden, 'services_label', 'nav_show_services', 'show_services', 'services_intro'];
        }

        if (! $template->supports('products')) {
            $hidden = [...$hidden, 'products_label', 'nav_show_products', 'show_products', 'products_intro'];
        }

        if (! $template->supports('blog')) {
            $hidden = [...$hidden, 'blog_label', 'nav_show_blog', 'show_blog', 'blog_intro'];
        }

        return array_map(function (array $group) use ($hidden): array {
            $group['fields'] = array_diff_key($group['fields'], array_flip($hidden));

            return $group;
        }, $groups);
    }

    public function edit(Request $request, Settings $settings): View
    {
        $groups = self::groups();
        $tab = array_key_exists($request->input('tab', ''), $groups) ? $request->input('tab') : 'general';

        return view('admin.settings.edit', ['groups' => $groups, 'tab' => $tab, 'values' => $settings->all()]);
    }

    public function update(Request $request, Settings $settings): RedirectResponse
    {
        $groups = self::groups();
        $tab = $request->input('tab');
        abort_unless(array_key_exists($tab, $groups), 404);

        $rules = [];
        foreach ($groups[$tab]['fields'] as $key => $field) {
            $rules[$key] = match ($field['type']) {
                'boolean' => ['nullable', 'boolean'],
                'image' => ['nullable', 'string', 'max:255'],
                default => array_merge(['nullable', 'string'], $field['rules'] ?? []),
            };

            if (in_array('required', $rules[$key], true)) {
                $rules[$key] = array_values(array_diff($rules[$key], ['nullable']));
            }
        }

        $labels = array_map(fn (array $field) => $field['label'], $groups[$tab]['fields']);
        $data = $request->validate($rules, [], $labels);

        foreach ($groups[$tab]['fields'] as $key => $field) {
            if ($field['type'] === 'boolean') {
                $data[$key] = $request->boolean($key) ? '1' : '0';
            }
        }

        $settings->setMany($data, $tab);
        Cache::flush();

        return redirect()->route('admin.settings.edit', ['tab' => $tab])->with('success', 'Ayarlar kaydedildi.');
    }

    public function clearCache(): RedirectResponse
    {
        Cache::flush();
        Artisan::call('view:clear');

        return back()->with('success', 'Önbellek temizlendi.');
    }
}
