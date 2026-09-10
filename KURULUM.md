# Kurulum

1. Dosyaları sunucuya yükleyin (örn. `~/site`).
2. `.env.production.example` dosyasını `.env` olarak kopyalayın ve doldurun:
   veritabanı bilgileri, `APP_URL`, mail ayarları, yönetici e-postası ve şifresi.
   `APP_TEMPLATE` değeri `templates/` altındaki şablon klasörünün adı olmalıdır
   (teslim paketlerinde tek şablon bulunur; değeri değiştirmeyin).
3. Kurulumu çalıştırın:

   ```bash
   bash deploy.sh                 # örnek içerikle: SEED_DEMO=1 bash deploy.sh
   ```

4. Yönetim paneli: `https://alanadiniz.com/admin/login`

Ayrıntılı adımlar ve cPanel seçenekleri için [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md).
Derlenmiş CSS/JS paketin içindedir; sunucuda Node.js gerekmez.
