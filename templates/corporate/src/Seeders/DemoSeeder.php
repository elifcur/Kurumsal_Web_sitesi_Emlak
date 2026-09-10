<?php

namespace Templates\Corporate\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\Settings;
use Illuminate\Database\Seeder;

/**
 * Sector-neutral demo content so a fresh install looks complete.
 * Idempotent: nothing is created when services already exist.
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (Service::query()->exists()) {
            return;
        }

        $settings = app(Settings::class);
        $settings->set('about_points', "Uzman ve deneyimli ekip\nŞeffaf fiyatlandırma\nZamanında teslimat\n7/24 destek hattı", 'template');
        $settings->set('about_quote', 'Müşterilerimizin başarısı, bizim başarımızdır.', 'template');

        foreach ([
            ['title' => 'Danışmanlık', 'icon' => 'briefcase', 'excerpt' => 'İhtiyaç analizi, strateji ve uygulama planı ile projelerinizi doğru temeller üzerine kuruyoruz.'],
            ['title' => 'Proje Yönetimi', 'icon' => 'chart', 'excerpt' => 'Bütçe, takvim ve kalite hedeflerini tek elden yöneterek projelerinizi zamanında teslim ediyoruz.'],
            ['title' => 'Teknik Destek', 'icon' => 'wrench', 'excerpt' => 'Kurulum sonrası bakım, arıza müdahalesi ve uzaktan destekle sistemlerinizi kesintisiz tutuyoruz.'],
            ['title' => 'Eğitim', 'icon' => 'academic-cap', 'excerpt' => 'Ekibinizi yeni süreç ve araçlara uyum sağlaması için yerinde ve online eğitimlerle destekliyoruz.'],
            ['title' => 'Kalite Güvence', 'icon' => 'shield', 'excerpt' => 'Uluslararası standartlara uygun denetim ve belgelendirme süreçlerinde yanınızdayız.'],
            ['title' => 'Ar-Ge & İnovasyon', 'icon' => 'sparkles', 'excerpt' => 'Ürün ve hizmetlerinizi geliştirecek yenilikçi çözümler için birlikte çalışıyoruz.'],
        ] as $index => $service) {
            Service::factory()->create($service + ['sort_order' => $index, 'is_featured' => $index < 3]);
        }

        $categories = collect(['Çözümler', 'Ekipmanlar'])->map(fn (string $name, int $i) => Category::factory()->create(['name' => $name, 'sort_order' => $i]));

        foreach ([
            ['Kurumsal Paket', 0, 24900, 'Orta ölçekli işletmeler için uçtan uca kurulum ve destek paketi.'],
            ['Profesyonel Paket', 0, 49900, 'Büyüyen ekipler için gelişmiş raporlama ve öncelikli destek.'],
            ['Başlangıç Paketi', 0, 9900, 'Küçük işletmeler için temel ihtiyaçları karşılayan ekonomik paket.'],
            ['Endüstriyel Sensör Seti', 1, 3750, 'Sıcaklık, nem ve titreşim ölçümü için endüstriyel sınıf sensörler.'],
            ['Kontrol Ünitesi', 1, 12500, 'Saha cihazlarını tek panelden yöneten modüler kontrol ünitesi.'],
            ['Yedek Parça Kiti', 1, 1450, 'Bir yıllık periyodik bakım için gerekli sarf malzemeleri.'],
        ] as $i => [$title, $categoryIndex, $price, $excerpt]) {
            Product::factory()->create([
                'title' => $title,
                'category_id' => $categories[$categoryIndex]->id,
                'price' => $price,
                'excerpt' => $excerpt,
                'specs' => [['label' => 'Garanti', 'value' => '2 yıl'], ['label' => 'Teslimat', 'value' => '3-5 iş günü']],
                'is_featured' => $i < 4,
                'sort_order' => $i,
            ]);
        }

        foreach ([
            ['Ahmet Yılmaz', 'Genel Müdür', [['label' => 'Deneyim', 'value' => '20 yıl']]],
            ['Zeynep Kaya', 'Operasyon Direktörü', [['label' => 'Deneyim', 'value' => '14 yıl']]],
            ['Murat Demir', 'Teknik Müdür', [['label' => 'Deneyim', 'value' => '11 yıl']]],
            ['Elif Arslan', 'Müşteri İlişkileri', [['label' => 'Deneyim', 'value' => '8 yıl']]],
        ] as $i => [$name, $title, $attributes]) {
            TeamMember::factory()->create(['name' => $name, 'title' => $title, 'attributes' => $attributes, 'sort_order' => $i, 'is_featured' => true]);
        }

        foreach ([
            ['Selin A.', 'Satın Alma Müdürü', 'Teklif aşamasından teslimata kadar her adımda şeffaf ve hızlı bir süreç yaşadık.'],
            ['Burak T.', 'İşletme Sahibi', 'Kurulum sonrası destek ekibi her sorumuza aynı gün dönüş yaptı. Kesinlikle tavsiye ederim.'],
            ['Deniz K.', 'Proje Yöneticisi', 'Proje planına sadık kaldılar ve bütçeyi aşmadan işi teslim ettiler.'],
        ] as $i => [$name, $title, $content]) {
            Testimonial::factory()->create(['name' => $name, 'title' => $title, 'content' => $content, 'rating' => 5, 'sort_order' => $i]);
        }

        foreach ([
            ['Teklif süreci nasıl işliyor?', 'İletişim formundan talebinizi iletin; ihtiyaç analizi sonrası 2 iş günü içinde detaylı teklifinizi hazırlıyoruz.'],
            ['Hangi bölgelere hizmet veriyorsunuz?', 'Türkiye genelinde yerinde hizmet, yurt dışında ise uzaktan destek sunuyoruz.'],
            ['Garanti kapsamı nedir?', 'Tüm ürünlerimiz 2 yıl, hizmetlerimiz ise sözleşmede belirtilen süre boyunca garanti kapsamındadır.'],
            ['Ödeme seçenekleri nelerdir?', 'Havale/EFT, kredi kartı ve kurumsal müşteriler için vadeli ödeme seçenekleri mevcuttur.'],
        ] as $i => [$question, $answer]) {
            Faq::factory()->create(['question' => $question, 'answer' => $answer, 'sort_order' => $i]);
        }

        foreach (['Nova Teknoloji', 'Atlas Holding', 'Delta Lojistik', 'Meridyen', 'Kuzey Enerji', 'Vega Yapı'] as $i => $name) {
            Partner::factory()->create(['name' => $name, 'sort_order' => $i]);
        }

        $category = Category::factory()->post()->create(['name' => 'Haberler']);
        $tags = collect(['sektör', 'duyuru', 'ipuçları'])->map(fn (string $name) => Tag::factory()->create(['name' => $name]));
        $author = User::query()->first();

        foreach (['Yeni Hizmet Paketlerimizi Duyurduk', 'Sektörde Dijital Dönüşüm: Nereden Başlamalı?', 'Müşteri Memnuniyetinde 5 Altın Kural', 'Yeni Ofisimize Taşındık'] as $title) {
            $post = Post::factory()->create(['title' => $title, 'user_id' => $author?->id, 'category_id' => $category->id]);
            $post->tags()->sync($tags->random(2)->pluck('id'));
        }
    }
}
