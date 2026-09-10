# Emlak Şablonu (`real-estate`)

Emlak ofisleri ve gayrimenkul danışmanları için hazır kurumsal site.

## Sektör içeriği
- **İlanlar** (`listings` tablosu): satılık/kiralık, tip, fiyat, oda, m², kat, ısıtma, özellikler, galeri, video, harita, sorumlu danışman.
- Public: `/ilanlar` (filtre + sıralama), `/ilanlar/{slug}` (galeri, detay, talep formu → Mesajlar'a "İlan Talebi" olarak düşer).
- Admin: **Emlak > İlanlar**.

## Kullanılan çekirdek özellikler
Hizmetler, Blog, Ekip (danışmanlar), Müşteri Yorumları, SSS, Partnerler. Ürünler kapalı.

## Şablon ayarları (Site Ayarları > Emlak Ayarları)
Menü etiketi, ana sayfa bölüm başlıkları, talep formu başarı mesajı.

## Ekip üyesi özellikleri
Danışman kartlarında `Bölge`, `Uzmanlık`, `Deneyim` etiketli özellikler gösterilir (Admin > Ekip > Özellikler).
