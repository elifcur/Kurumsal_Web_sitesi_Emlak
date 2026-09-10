#!/usr/bin/env bash
# ------------------------------------------------------------------
# Corporate Templates - cPanel / paylaşımlı hosting deploy betiği
# Kullanım (SSH ile uygulama dizininde):  bash deploy.sh
# Demo içerik de yüklemek için:            SEED_DEMO=1 bash deploy.sh
# ------------------------------------------------------------------
set -euo pipefail

PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"

cd "$(dirname "$0")"

if [ ! -f .env ]; then
    echo "!! .env bulunamadı. .env.production.example dosyasını .env olarak kopyalayıp düzenleyin."
    exit 1
fi

TEMPLATE="$(grep -E '^APP_TEMPLATE=' .env | cut -d= -f2- | tr -d '"' || true)"
if [ -z "$TEMPLATE" ] || [ ! -d "templates/$TEMPLATE" ]; then
    echo "!! APP_TEMPLATE geçersiz ('$TEMPLATE'). templates/ altındaki bir klasör olmalı: $(ls templates | tr '\n' ' ')"
    exit 1
fi
echo "==> Aktif şablon: $TEMPLATE"

echo "==> Bakım modu açılıyor"
$PHP_BIN artisan down --retry=30 || true

echo "==> Composer bağımlılıkları (production)"
$COMPOSER_BIN install --no-dev --optimize-autoloader --no-interaction --prefer-dist

if ! grep -q '^APP_KEY=base64' .env; then
    echo "==> APP_KEY üretiliyor"
    $PHP_BIN artisan key:generate --force
fi

echo "==> Dizin izinleri"
mkdir -p storage/app/purifier storage/framework/{cache,sessions,views} storage/logs public/uploads
chmod -R ug+rwX storage bootstrap/cache public/uploads

echo "==> Veritabanı migration (çekirdek + $TEMPLATE)"
$PHP_BIN artisan migrate --force --no-interaction

echo "==> Varsayılan veriler (yönetici, ayarlar, sayfalar; yalnızca eksik olanlar eklenir)"
$PHP_BIN artisan db:seed --force --no-interaction

if [ "${SEED_DEMO:-0}" = "1" ]; then
    echo "==> Demo içerik yükleniyor"
    $PHP_BIN artisan db:seed --force --no-interaction --class='Database\Seeders\DemoSeeder'
fi

echo "==> Önbellekler"
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan optimize

echo "==> Bakım modu kapatılıyor"
$PHP_BIN artisan up

echo "==> Tamamlandı."
