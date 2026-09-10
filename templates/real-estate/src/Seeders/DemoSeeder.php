<?php

namespace Templates\RealEstate\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Service;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\Settings;
use Illuminate\Database\Seeder;
use Templates\RealEstate\Models\Listing;

/**
 * Demo content that makes the real-estate site look complete out of the box.
 * Idempotent: nothing is created when listings already exist.
 */
class DemoSeeder extends Seeder
{
    private const IMAGE_PATH = '/images/templates/real-estate';

    public function run(): void
    {
        if (Listing::query()->exists()) {
            return;
        }

        $settings = app(Settings::class);
        $settings->set('logo', self::IMAGE_PATH.'/logo.png', 'general');
        $settings->set('hero_image', self::IMAGE_PATH.'/hero.jpg', 'home');
        $settings->set('hero_card_title', '', 'template'); // demo fotoğrafında kart zaten var

        $services = [
            ['title' => 'Satılık Konut Danışmanlığı', 'icon' => 'home', 'excerpt' => 'Bütçenize ve yaşam tarzınıza uygun evi bulur, pazarlık ve tapu sürecini yönetiriz.'],
            ['title' => 'Kiralama Yönetimi', 'icon' => 'key', 'excerpt' => 'Mülkünüzü doğru kiracıyla buluşturur, sözleşme ve tahsilat takibini üstleniriz.'],
            ['title' => 'Gayrimenkul Değerleme', 'icon' => 'chart', 'excerpt' => 'Bölge analizi ve emsal karşılaştırmasıyla mülkünüzün gerçek piyasa değerini belirleriz.'],
            ['title' => 'Yatırım Danışmanlığı', 'icon' => 'currency', 'excerpt' => 'Getiri potansiyeli yüksek projeleri ve arsaları sizin için analiz ederiz.'],
        ];

        foreach ($services as $index => $service) {
            Service::factory()->create($service + ['sort_order' => $index, 'is_featured' => true]);
        }

        $agents = collect([
            ['name' => 'Ayşe Demir', 'title' => 'Kurucu Ortak · Kıdemli Danışman', 'attributes' => [['label' => 'Bölge', 'value' => 'Bodrum, Çeşme'], ['label' => 'Uzmanlık', 'value' => 'Lüks konut, villa'], ['label' => 'Deneyim', 'value' => '15 yıl']]],
            ['name' => 'Mehmet Kaya', 'title' => 'Gayrimenkul Danışmanı', 'attributes' => [['label' => 'Bölge', 'value' => 'Maslak, Ataşehir'], ['label' => 'Uzmanlık', 'value' => 'Ticari gayrimenkul'], ['label' => 'Deneyim', 'value' => '9 yıl']]],
            ['name' => 'Elif Şahin', 'title' => 'Kiralama Uzmanı', 'attributes' => [['label' => 'Bölge', 'value' => 'Kadıköy, Üsküdar'], ['label' => 'Uzmanlık', 'value' => 'Kiralık konut'], ['label' => 'Deneyim', 'value' => '6 yıl']]],
            ['name' => 'Can Yıldız', 'title' => 'Yatırım Danışmanı', 'attributes' => [['label' => 'Bölge', 'value' => 'Çekmeköy, Beykoz'], ['label' => 'Uzmanlık', 'value' => 'Arsa, proje'], ['label' => 'Deneyim', 'value' => '11 yıl']]],
        ])->map(fn (array $agent, int $i) => TeamMember::factory()->create($agent + ['sort_order' => $i, 'is_featured' => true]));

        /*
         * İlk dört ilan mockup'taki kartlarla birebir (villa, daire, arsa, ofis);
         * listing-1..4.jpg görselleri sırayla, kalan ilanlara döngüyle atanır.
         */
        $listings = [
            ['Deniz Manzaralı Lüks Villa', 'sale', 'villa', 'Muğla', 'Bodrum', '5+1', 320, 42000000],
            ['Merkezi Konumda Modern Daire', 'rent', 'apartment', 'İstanbul', 'Kadıköy', '3+1', 150, 45000],
            ['Yatırımlık Arsa', 'sale', 'land', 'İzmir', 'Çeşme', null, 500, 8500000],
            ['Prestijli Ofis Alanı', 'rent', 'office', 'İstanbul', 'Maslak', null, 250, 120000],
            ['Site İçinde Havuzlu 4+1 Villa', 'sale', 'villa', 'İstanbul', 'Sarıyer', '4+1', 380, 24500000],
            ['Metroya Yürüme Mesafesinde 2+1', 'rent', 'apartment', 'İstanbul', 'Kadıköy', '2+1', 95, 32000],
            ['Yeni Bina Eşyalı 1+1 Rezidans', 'rent', 'residence', 'İstanbul', 'Maltepe', '1+1', 70, 27500],
            ['Bahçeli Müstakil Ev', 'sale', 'villa', 'İstanbul', 'Beykoz', '5+2', 410, 18900000],
            ['Ana Cadde Üzerinde Dükkan', 'sale', 'shop', 'İstanbul', 'Üsküdar', null, 120, 9800000],
            ['Ferah 3+1 Aile Dairesi', 'sale', 'apartment', 'İstanbul', 'Ataşehir', '3+1', 165, 6450000],
        ];

        foreach ($listings as $i => [$title, $type, $property, $city, $district, $rooms, $area, $price]) {
            Listing::factory()->create([
                'title' => $title,
                'listing_type' => $type,
                'property_type' => $property,
                'city' => $city,
                'district' => $district,
                'rooms' => $rooms,
                'bathrooms' => $rooms === null ? null : null,
                'area_gross' => $area,
                'area_net' => (int) round($area * 0.85),
                'price' => $price,
                'image' => self::IMAGE_PATH.'/listing-'.(($i % 4) + 1).'.jpg',
                'is_featured' => $i < 4,
                'agent_id' => $agents[$i % $agents->count()]->id,
                'sort_order' => $i,
            ]);
        }

        foreach ([
            ['Zeynep A.', 'Ev sahibi', 'Dairemizi iki hafta içinde beklediğimizden yüksek fiyata sattılar. Süreç boyunca her adımı şeffaf şekilde paylaştılar.'],
            ['Burak T.', 'Yatırımcı', 'Arsa yatırımı için bölge analizi yaptılar, çok doğru bir yönlendirme oldu. Kesinlikle tavsiye ederim.'],
            ['Selin K.', 'Kiracı', 'İhtiyacımıza uygun evi ilk turda bulduk. Sözleşme ve teslim süreçleri sorunsuz ilerledi.'],
        ] as $i => [$name, $title, $content]) {
            Testimonial::factory()->create(['name' => $name, 'title' => $title, 'content' => $content, 'rating' => 5, 'sort_order' => $i]);
        }

        foreach ([
            ['Danışmanlık hizmetiniz ücretli mi?', 'Alıcılar için ilan inceleme ve gezme hizmetimiz ücretsizdir. Satış gerçekleştiğinde yasal komisyon oranı uygulanır.'],
            ['Değerleme ne kadar sürer?', 'Yerinde inceleme sonrası 2 iş günü içinde emsal analizli değerleme raporunuzu iletiyoruz.'],
            ['Kredi sürecinde destek veriyor musunuz?', 'Evet, anlaşmalı bankalarla en uygun konut kredisi seçeneklerini karşılaştırıp başvuru sürecinizi takip ediyoruz.'],
            ['Tapu işlemlerini siz mi yürütüyorsunuz?', 'Randevu, harç hesaplama ve tapu devri dahil tüm işlemleri danışmanınız sizinle birlikte yürütür.'],
        ] as $i => [$question, $answer]) {
            Faq::factory()->create(['question' => $question, 'answer' => $answer, 'sort_order' => $i]);
        }

        foreach (['Emlak Konut', 'Sinpaş', 'Ağaoğlu', 'DAP Yapı', 'Nef', 'Tekfen'] as $i => $name) {
            Partner::factory()->create(['name' => $name, 'sort_order' => $i]);
        }

        $category = Category::factory()->post()->create(['name' => 'Emlak Rehberi']);
        $tags = collect(['konut kredisi', 'kiralama', 'yatırım'])->map(fn ($n) => Tag::factory()->create(['name' => $n]));
        $author = User::query()->first();

        foreach (['Ev Alırken Dikkat Edilmesi Gereken 10 Nokta', 'Kira Sözleşmesinde Bilmeniz Gerekenler', '2026 Konut Kredisi Rehberi', 'Yatırımlık Arsa Nasıl Seçilir?'] as $title) {
            $post = Post::factory()->create(['title' => $title, 'user_id' => $author?->id, 'category_id' => $category->id]);
            $post->tags()->sync($tags->random(2)->pluck('id'));
        }
    }
}
