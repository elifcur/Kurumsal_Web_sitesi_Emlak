# cPanel Deploy Rehberi

Bu uygulama klasik cPanel paylaşımlı hosting (PHP 8.3+, MySQL/MariaDB, Composer) üzerinde çalışacak şekilde hazırlanmıştır. Her müşteriye **tek bir sektör şablonu** ile teslim edilir; şablon `.env` içindeki `APP_TEMPLATE` ile seçilir.

## Gereksinimler

| Bileşen | Minimum |
|---|---|
| PHP | 8.3 (önerilen 8.4 / 8.5) — `mbstring`, `pdo_mysql`, `fileinfo`, `gd`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath` |
| MySQL / MariaDB | MySQL 5.7+ veya MariaDB 10.3+ (utf8mb4) |
| Composer | 2.x (SSH ile ya da cPanel "Terminal" üzerinden) |
| Node.js | **Gerekmez.** Derlenmiş varlıklar `public/build` içinde depoya dahildir. |

cPanel > *Select PHP Version* ekranından PHP sürümünü ve eklentileri seçin. `upload_max_filesize` ve `post_max_size` değerlerini en az `8M` yapın (`CMS_UPLOAD_MAX_KB` ile uyumlu olmalı).

## 0. Teslim paketi hazırlama (geliştirici makinesinde)

```bash
php artisan template:use clinic      # müşterinin şablonu
npm run build                        # public/build güncel olsun (repoya commit'lidir)
php artisan template:prune --force   # diğer şablon klasörlerini sil (isteğe bağlı, önerilir)
```

Prune sonrası yalnızca `templates/clinic` kalır; `public/build` içindeki diğer şablon varlıkları zararsızdır.

## 1. Dosyaları yükleme

**Seçenek A – Git (önerilen):** cPanel > *Git Version Control* ile depoyu `~/site` dizinine klonlayın.

**Seçenek B – ZIP:** Projeyi (`vendor` ve `node_modules` hariç) zip'leyip *File Manager* ile `~/site` altına açın.

## 2. Document root seçimi

### A) Document root'u `public` yapmak (en temiz yöntem)

Addon domain / subdomain oluştururken **Document Root** alanına `site/public` yazın. Ana domain için bazı hostlar document root değişikliğine izin vermez; o durumda B seçeneğini kullanın.

### B) Ana domain (`public_html`) için

1. `deploy/cpanel/index.php` dosyasını `public_html/` içine kopyalayın, `public/.htaccess` dosyasını da `public_html/.htaccess` olarak kopyalayın.
2. `public_html/index.php` içindeki `$appDir` değerini uygulama dizininize göre düzenleyin (varsayılan `../site`).
3. `public/build`, `public/favicon.ico` ve `public/uploads` (içindeki `.htaccess` dahil) klasörlerini `public_html/` altına kopyalayın.
4. `.env` dosyasına ekleyin:
   ```
   FILESYSTEM_UPLOADS_ROOT=/home/cpaneluser/public_html/uploads
   ```

### C) Her şey `public_html` içinde (son çare)

Projeyi doğrudan `public_html` içine açarsanız kökteki `.htaccess` istekleri `public/` altına yönlendirir ve `app/`, `templates/`, `vendor/`, `.env` gibi dizin/dosyaları engeller. Yine de A veya B tercih edilmelidir.

## 3. Ortam dosyası

```bash
cp .env.production.example .env
nano .env   # APP_TEMPLATE, DB_*, APP_URL, MAIL_*, ADMIN_* değerlerini doldurun
```

Zorunlu değerler: `APP_TEMPLATE`, `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://...`, `SESSION_SECURE_COOKIE=true` (HTTPS kullanıyorsanız).

## 4. Kurulum komutları (SSH / cPanel Terminal)

```bash
cd ~/site
bash deploy.sh              # SEED_DEMO=1 bash deploy.sh  → örnek içerikle
```

`deploy.sh` sırasıyla: şablon kontrolü, composer install (no-dev), APP_KEY, izinler, migration (çekirdek + şablon), seed (idempotent), `optimize` çalıştırır.

> `storage:link` **gerekmez**; yüklemeler `public/uploads` içine yazılır.

## 5. İlk giriş

`https://alanadiniz.com/admin/login` — `.env` içindeki `ADMIN_EMAIL` / `ADMIN_PASSWORD` ile giriş yapın ve **şifreyi hemen değiştirin** (Profilim > Şifre Değiştir).

Sonra: Site Ayarları (logo, iletişim, SEO, şablon ayarları sekmesi) → içerikler.

## 6. Güncelleme

```bash
cd ~/site
git pull            # ya da dosyaları yeniden yükleyin
bash deploy.sh
```

## 7. Şablon değiştirme (aynı sunucuda)

```bash
php artisan template:use industry --seed
php artisan optimize
```

Eski şablonun tabloları silinmez; yeni şablonun migration'ları çalışır, ayarlar yeni şablonun varsayılanlarıyla üzerine yazılır.

## 8. Cron (isteğe bağlı)

Zamanlanmış blog yazıları yayın tarihine göre otomatik görünür; cron gerekmez. Laravel scheduler kullanmak isterseniz cPanel > Cron Jobs:

```
* * * * * /usr/local/bin/php /home/cpaneluser/site/artisan schedule:run >> /dev/null 2>&1
```

## 9. Sorun giderme

| Belirti | Çözüm |
|---|---|
| "Şablon bulunamadı" hatası | `.env` içindeki `APP_TEMPLATE` ile `templates/` klasör adı aynı olmalı; `php artisan optimize:clear` |
| 500 hata, beyaz sayfa | `storage/logs/laravel.log` kontrol edin; `chmod -R ug+rwX storage bootstrap/cache` |
| Değişiklikler görünmüyor | `php artisan optimize:clear && php artisan optimize` |
| "Unable to locate file in Vite manifest" | `public/build` klasörünün yüklendiğinden emin olun |
| Görseller yüklenmiyor | `public/uploads` yazılabilir mi? PHP `upload_max_filesize` yeterli mi? |
| 419 hatası (form) | `APP_URL` ve `SESSION_DOMAIN` doğru mu? HTTPS ise `SESSION_SECURE_COOKIE=true` |
