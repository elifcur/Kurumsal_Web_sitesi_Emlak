# Corporate Templates

Laravel tabanlı, cPanel paylaşımlı hosting'de çalışan **kurumsal web sitesi şablonları** altyapısı.

Hazır bir CMS (Statamic, October vb.) kurup kullanmıyoruz. Kendi ihtiyacımıza göre **Laravel 13 + MySQL/MariaDB + kendi admin panelimiz + kendi sektör şablonlarımız**: her müşteriye kendi sektörüne özel, tasarımı ve içeriği hazır, tek komutla teslim edilebilen bir site.

## Şablonlar

| Anahtar | Sektör | Sektöre özel içerik |
|---|---|---|
| `real-estate` | Emlak | İlanlar (satılık/kiralık, filtre, galeri, talep formu), danışmanlar |
| `clinic` | Sağlık / Klinik | Tedaviler, doktorlar, online randevu |
| `industry` | Sanayi / Üretim | Ürün kataloğu, makine parkuru, sertifikalar, teklif formu |
| `law` | Hukuk / Danışmanlık | Çalışma alanları, avukatlar, ön görüşme talebi, kaynaklar |
| `business` | Küçük İşletme / Esnaf | Fiyat listesi, çalışma galerisi, ürünler, hızlı teklif/sipariş formu |

Tek kod tabanı, `.env` içindeki `APP_TEMPLATE` ile seçilen **tek aktif şablon**. Her şablon `templates/<anahtar>/` altında kendi tasarımını (Blade + Tailwind), sektör modellerini, admin ekranlarını, rotalarını, demo içeriğini ve varsayılan ayarlarını taşır.

## Ortak çekirdek (tüm şablonlarda)

- **Admin paneli** (`/admin`): Dashboard, Sayfalar, Hizmetler, Ürünler + Kategoriler, Blog, Ekip, Müşteri Yorumları, SSS, Partnerler, Medya kütüphanesi, Mesajlar, Site Ayarları, Kullanıcılar & roller, Profil. Şablon istemediği bölümleri kapatır (örn. hukuk sitesinde Ürünler yoktur).
- **SEO:** dinamik meta/OG, canonical, JSON-LD (Organization, BreadcrumbList), `sitemap.xml`, `robots.txt`, Türkçe slug'lar.
- **Güvenlik:** rol tabanlı yetki, CSRF, Form Request doğrulama, HTML Purifier, güvenli yükleme + görsel yeniden kodlama, rate limiting, honeypot, güvenlik başlıkları.
- **cPanel uyumu:** `public/uploads` (symlink gerekmez), derlenmiş varlıklar repoda (`public/build`, Node gerekmez), MySQL 5.7+/MariaDB uyumlu index uzunlukları.

## Geliştirme

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed        # local: aktif şablonun demo içeriğini de yükler
npm run build                     # tüm şablonların CSS/JS'ini derler
php artisan serve
```

Varsayılan yönetici: `admin@example.com` / `password` (`.env` içindeki `ADMIN_*`).

### Şablon komutları

```bash
php artisan template:list                 # kurulu şablonlar, aktif olan işaretli
php artisan template:use clinic --seed    # şablonu değiştir, ayar + demo içeriğini yükle
php artisan template:prune                # aktif şablon dışındakileri sil (teslim öncesi)
```

### Müşteriye gösterme (vitrin)

Beş sektör sitesini aynı anda ayrı portlarda açar. Hiçbir şablonu silmez, projenin
`.env` dosyasına ve veritabanına dokunmaz; her site kendi demo veritabanını kullanır.

```bash
bash demo.sh start     # 8001 Emlak · 8002 Klinik · 8003 Sanayi · 8004 Hukuk · 8005 İşletme
bash demo.sh status    # adresler ve durum
bash demo.sh stop
bash demo.sh reset     # demo içeriğini sıfırla
```

### Müşteriye teslim paketi

Seçilen şablonu aktif eder, **diğer şablonları ve demo görsellerini siler**, sunucuya
yüklenmeye hazır bir klasör üretir. Kaynak projeye dokunmaz.

```bash
bash teslim.sh law                  # ../teslim-law klasörünü oluşturur
bash teslim.sh real-estate ~/Masaüstü/abc-emlak
```

Çıkan klasörde `KURULUM.md` adım adım kurulumu anlatır. `vendor` ve `node_modules`
pakete dahil değildir; sunucuda `bash deploy.sh` bunları kurar.

Testler: `php artisan test` (aktif şablonla). Tüm şablonlar için: `composer test:templates`.

## Deploy

cPanel kurulum adımları için [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md). Kısaca: `.env.production.example` → `.env` (`APP_TEMPLATE` seç), ardından `bash deploy.sh`.

## Yeni şablon ekleme

1. `templates/<anahtar>/` klasörünü `templates/real-estate` yapısında oluşturun (`src/Template.php`, `views/`, `css/app.css`, `js/app.js`, `routes.php`, `database/migrations/`, `src/Seeders/DemoSeeder.php`).
2. `Template` sınıfında `features()`, `defaultSettings()`, `adminMenu()`, `publicMenu()`, `homeData()` tanımlayın.
3. `npm run build` ile derleyin; `APP_TEMPLATE=<anahtar> php artisan test` ile doğrulayın.

Autoload otomatiktir (`Templates\<Studly>\...` → `templates/<anahtar>/src/`), composer.json'a dokunmak gerekmez.
