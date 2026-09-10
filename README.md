# Kurumsal Web Sitesi & CMS

Laravel 13 tabanlı, cPanel paylaşımlı hosting'de çalışan, **farklı sektörlerde yeniden kullanılabilen** kurumsal web sitesi altyapısı: ortak bir CMS çekirdeği + sektöre özel şablonlar.

Her kurulumda `.env` içindeki `APP_TEMPLATE` ile seçilen **tek şablon** aktiftir. Şablonlar `templates/<anahtar>/` altında yaşar; çekirdek (`app/`, `resources/`) sektör kodu içermez.

## Şablonlar

| Anahtar | Ad | İçerik |
|---|---|---|
| `corporate` | Kurumsal (varsayılan) | Sektörden bağımsız: hizmetler, ürün kataloğu, blog, ekip, referanslar, SSS, partnerler |
| `real-estate` | Emlak | İlan portföyü (satılık/kiralık, filtre, galeri, talep formu), danışman ekibi |

`php artisan template:list` kurulu şablonları gösterir.

## Nasıl çalışır

- **Çekirdek CMS** — Admin paneli (`/admin`): Sayfalar, Hizmetler, Ürünler + Kategoriler, Blog, Ekip, Müşteri Yorumları, SSS, Partnerler, Medya, Mesajlar, Site Ayarları, Kullanıcılar & roller. Şablon `features()` ile istemediği modülleri kapatır; kapalı modülün rotası, menüsü ve ayarı görünmez.
- **Fallback view'lar** — `resources/views/site/` altında nötr layout, header/footer, hizmet/ürün/blog/ekip/sayfa/iletişim sayfaları ve kart bileşenleri bulunur. Şablon yalnızca `views/home.blade.php` sağlamak zorundadır; diğer her sayfayı ve bileşeni aynı göreli yolla `views/` altına koyarak ezebilir (`templates/real-estate/views/partials/footer-links.blade.php` örneği).
- **Tasarım tokenları** — `resources/css/base.css` ortak sınıfları (`.btn-primary`, `.section`, `.card`, `.nav-link`…) ve varsayılan renkleri tanımlar. Şablon `css/app.css` içindeki `@theme` bloğunda renkleri değiştirir.
- **Şablon kancaları** — `Template` sınıfı: `features()`, `defaultSettings()`, `settingFields()` (Site Ayarları'nda otomatik sekme), `adminMenu()`, `publicMenu()`, `dashboardStats()`, `sitemapEntries()`, `messageTypes()`, `contactSubjects()`, `icon()`, `fonts()`, `homeData()`.
- **SEO & güvenlik** — dinamik meta/OG, canonical, JSON-LD, `sitemap.xml`, `robots.txt`; rol tabanlı yetki, CSRF, Form Request, HTML Purifier, güvenli yükleme, rate limiting, honeypot, güvenlik başlıkları.
- **cPanel uyumu** — `public/uploads` (symlink gerekmez), derlenmiş varlıklar repoda (`public/build`), MySQL 5.7+/MariaDB uyumlu index uzunlukları.

## Geliştirme

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed        # aktif şablonun demo içeriğini de yükler (local)
npm run build                     # tüm şablonların CSS/JS'ini derler (ya da: npm run dev)
php artisan serve
```

Varsayılan yönetici: `admin@example.com` / `password` (`.env` içindeki `ADMIN_*`).

```bash
php artisan template:use real-estate --seed   # şablonu değiştir, ayar + demo içeriğini yükle
php artisan test                              # aktif şablonla
composer test:templates                       # tüm şablonlarla
```

## Yeni şablon ekleme

1. `templates/corporate` klasörünü `templates/<anahtar>/` olarak kopyalayın.
2. `src/Template.php` içinde `key()`, `name()`, `description()`, `features()`, `defaultSettings()` değerlerini güncelleyin.
3. `css/app.css` içindeki `@theme` renklerini değiştirin; `views/home.blade.php` dosyasını uyarlayın.
4. Sektör modeli gerekiyorsa `templates/real-estate` yapısını izleyin: `routes.php`, `database/migrations/`, `src/Models`, `src/Http`, `views/admin/…`.
5. `npm run build` ile derleyin; `APP_TEMPLATE=<anahtar> php artisan test` ile doğrulayın.

`Templates\<Studly>\…` ad alanı `templates/<anahtar>/src/` dizinine otomatik autoload edilir; `composer.json` düzenlemek gerekmez.

## Müşteriye teslim

```bash
php artisan template:use <anahtar>   # müşterinin şablonu
npm run build                        # public/build güncel olsun
php artisan template:prune --force   # diğer şablon klasörlerini sil
```

Sunucu kurulumu için [KURULUM.md](KURULUM.md) (kısa) ve [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md) (cPanel ayrıntıları).
