# Emlak Sitesi

Laravel tabanlı, cPanel paylaşımlı hosting'de çalışan **emlak ofisi web sitesi** ve yönetim paneli.

Tek müşteriye teslim edilmek üzere hazırlanmış paket: sektöre özel tasarım, içerik ve admin ekranları hazır gelir. Site tasarımı ve sektör modülleri `templates/real-estate/` altında toplanmıştır; çekirdek CMS (`app/`, `resources/`) şablondan bağımsızdır.

## Özellikler

**Emlak modülü** (`templates/real-estate`)
- İlanlar: satılık / kiralık, tip, fiyat, oda, m², kat, ısıtma, özellikler, galeri, video, harita, sorumlu danışman
- `/ilanlar` filtre + sıralama, `/ilanlar/{slug}` detay ve talep formu (Mesajlar'a "İlan Talebi" olarak düşer)
- Danışman kadrosu (Ekip modülü; Bölge / Uzmanlık / Deneyim etiketleri)
- Site Ayarları > Emlak Ayarları: menü etiketi, ana sayfa bölüm başlıkları, talep formu mesajı

**Ortak çekirdek**
- **Admin paneli** (`/admin`): Dashboard, Sayfalar, Hizmetler, Blog, Ekip, Müşteri Yorumları, SSS, Partnerler, Medya kütüphanesi, Mesajlar, Site Ayarları, Kullanıcılar & roller, Profil. (Ürünler modülü bu şablonda kapalıdır.)
- **SEO:** dinamik meta/OG, canonical, JSON-LD (Organization, BreadcrumbList), `sitemap.xml`, `robots.txt`, Türkçe slug'lar
- **Güvenlik:** rol tabanlı yetki, CSRF, Form Request doğrulama, HTML Purifier, güvenli yükleme + görsel yeniden kodlama, rate limiting, honeypot, güvenlik başlıkları
- **cPanel uyumu:** `public/uploads` (symlink gerekmez), derlenmiş varlıklar repoda (`public/build`, sunucuda Node gerekmez), MySQL 5.7+/MariaDB uyumlu index uzunlukları

## Gereksinimler

| Bileşen | Sürüm |
|---|---|
| PHP | 8.3+ (`mbstring`, `pdo_mysql`, `fileinfo`, `gd`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`) |
| MySQL / MariaDB | MySQL 5.7+ / MariaDB 10.3+ (utf8mb4) — geliştirmede SQLite |
| Composer | 2.x |
| Node.js | Yalnızca geliştirmede (varlıkları derlemek için) |

## Geliştirme

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed        # şemayı ve demo içeriği yükler
npm run build                     # ya da: npm run dev
php artisan serve
```

Varsayılan yönetici: `admin@example.com` / `password` (`.env` içindeki `ADMIN_*` ile değiştirilir).

Testler:

```bash
php artisan test          # tümü
composer test             # config:clear + test
```

## Kurulum (sunucu)

Kısa yol için [KURULUM.md](KURULUM.md), cPanel'e özel tüm adımlar ve sorun giderme için [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md).

Özetle: `.env.production.example` dosyasını `.env` olarak kopyalayıp doldurun, ardından:

```bash
bash deploy.sh            # örnek içerikle: SEED_DEMO=1 bash deploy.sh
```

`deploy.sh` sırasıyla şablon kontrolü, `composer install --no-dev`, `APP_KEY`, dosya izinleri, migration, seed ve `optimize` adımlarını çalıştırır. `storage:link` gerekmez; yüklemeler `public/uploads` içine yazılır.

## Dizin yapısı

```
app/                    Çekirdek CMS: modeller, admin controller'ları, servisler
resources/views/        Admin paneli ve ortak layout'lar
templates/real-estate/  Emlak şablonu
  ├─ src/               Template.php, modeller, controller'lar, seeder'lar
  ├─ views/             Site tasarımı (Blade)
  ├─ css/ js/           Tailwind kaynakları (vite girdisi)
  ├─ routes.php         Şablona özel rotalar
  └─ database/          Şablona özel migration'lar
public/build/           Derlenmiş CSS/JS — repoya dahildir, sunucuda Node gerekmez
deploy/cpanel/          public_html'e kopyalanacak index.php ve .htaccess
docs/DEPLOYMENT.md      cPanel deploy rehberi
```

Aktif şablon `.env` içindeki `APP_TEMPLATE` ile seçilir (bu pakette `real-estate`). `php artisan template:list` kurulu şablonları gösterir. `Templates\RealEstate\...` ad alanı `templates/real-estate/src/` dizinine otomatik autoload edilir; `composer.json` düzenlemek gerekmez.
