<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Hakkımızda',
                'slug' => 'hakkimizda',
                'excerpt' => 'Firmamız, kuruluşundan bu yana kalite ve güven ilkeleriyle hizmet vermektedir.',
                'content' => '<h2>Biz Kimiz?</h2><p>Firmamız, sektöründe uzun yıllara dayanan deneyimi ile müşterilerine en kaliteli hizmeti sunmayı hedeflemektedir. Uzman kadromuz ve modern altyapımızla her projeye özel çözümler üretiyoruz.</p><h2>Misyonumuz</h2><p>Müşteri memnuniyetini en üst seviyede tutarak, güvenilir ve sürdürülebilir hizmetler sunmak.</p><h2>Vizyonumuz</h2><p>Sektörümüzde yenilikçi yaklaşımlarla öncü ve tercih edilen marka olmak.</p>',
                'show_in_menu' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Gizlilik Politikası',
                'slug' => 'gizlilik-politikasi',
                'excerpt' => 'Kişisel verilerinizin gizliliğine önem veriyoruz.',
                'content' => '<p>Bu gizlilik politikası, web sitemizi ziyaret eden kullanıcıların kişisel verilerinin nasıl toplandığını, kullanıldığını ve korunduğunu açıklamaktadır.</p><h2>Toplanan Bilgiler</h2><p>İletişim formları aracılığıyla paylaştığınız ad, e-posta ve telefon bilgileri yalnızca sizinle iletişim kurmak amacıyla kullanılır.</p><h2>Çerezler</h2><p>Sitemiz, kullanıcı deneyimini iyileştirmek amacıyla çerezler kullanabilir.</p>',
                'show_in_footer' => true,
                'sort_order' => 10,
            ],
            [
                'title' => 'KVKK Aydınlatma Metni',
                'slug' => 'kvkk',
                'excerpt' => '6698 sayılı Kişisel Verilerin Korunması Kanunu kapsamında aydınlatma metni.',
                'content' => '<p>6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca, veri sorumlusu sıfatıyla kişisel verileriniz aşağıda açıklanan kapsamda işlenmektedir.</p><h2>Kişisel Verilerin İşlenme Amacı</h2><p>Kişisel verileriniz; taleplerinizin karşılanması, hizmetlerimizin sunulması ve yasal yükümlülüklerin yerine getirilmesi amacıyla işlenmektedir.</p><h2>Haklarınız</h2><p>KVKK\'nın 11. maddesi kapsamında veri sorumlusuna başvurarak kişisel verilerinizle ilgili haklarınızı kullanabilirsiniz.</p>',
                'show_in_footer' => true,
                'sort_order' => 11,
            ],
        ];

        foreach ($pages as $data) {
            if (Page::query()->where('slug', $data['slug'])->exists()) {
                continue;
            }

            $page = new Page($data);
            $page->is_published = true;
            $page->published_at = now();
            $page->is_system = true;
            $page->save();
        }
    }
}
