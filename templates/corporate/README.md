# Kurumsal Şablon (`corporate`)

Sektörden bağımsız kurumsal site. Sektör modeli taşımaz; ana sayfa dışındaki
tüm sayfaları (hizmetler, ürünler, blog, ekip, sayfalar, iletişim) ve
bileşenleri `resources/views/site` altındaki çekirdek fallback view'lardan alır.

Yeni bir sektör şablonu yazarken başlangıç noktası olarak bu klasörü kopyalayın:

1. `src/Template.php` içinde `key()`, `name()`, `description()`, `features()` ve `defaultSettings()` değerlerini güncelleyin.
2. `css/app.css` içindeki `@theme` renklerini sektöre göre değiştirin.
3. `views/home.blade.php` dosyasını uyarlayın; değiştirmek istediğiniz diğer sayfaları aynı göreli yolla `views/` altına kopyalayın (şablondaki dosya çekirdeği ezer).
4. Sektör modeli gerekiyorsa `templates/real-estate` yapısına bakın (`routes.php`, `database/migrations`, `src/Models`, `src/Http`).
