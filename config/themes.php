<?php

return [

    'default' => 'corporate',

    /*
    |--------------------------------------------------------------------------
    | Fonts
    |--------------------------------------------------------------------------
    | Self-hosted through the Vite fonts plugin (see vite.config.js). Keys are
    | the font aliases; values are the CSS font-family names.
    */

    'fonts' => [
        'inter' => 'Inter',
        'manrope' => 'Manrope',
        'dm-sans' => 'DM Sans',
    ],

    /*
    |--------------------------------------------------------------------------
    | Themes
    |--------------------------------------------------------------------------
    | Each theme may override any Blade view by placing a file with the same
    | relative path under resources/views/themes/{key}/ (e.g. public/home.blade.php,
    | partials/public/header.blade.php). Missing files fall back to the default views.
    */

    'available' => [
        'corporate' => [
            'name' => 'Kurumsal',
            'description' => 'Klasik kurumsal düzen: koyu hero, ikon kartlı hizmetler, istatistik şeridi. Danışmanlık, sanayi, teknik servis ve genel kurumsal siteler için.',
            'font' => 'inter',
            'primary_color' => '#1d4ed8',
            'suits' => ['Danışmanlık', 'Sanayi', 'Teknik Servis', 'Genel'],
            'preview' => 'images/themes/corporate.png',
        ],
        'modern' => [
            'name' => 'Modern',
            'description' => 'Koyu üst menü, büyük tipografili hero, bento tarzı hizmet kartları ve yuvarlatılmış butonlar. Teknoloji, ajans ve yenilikçi markalar için.',
            'font' => 'manrope',
            'primary_color' => '#7c3aed',
            'suits' => ['Teknoloji', 'Ajans', 'Danışmanlık', 'Kitapçı'],
            'preview' => 'images/themes/modern.png',
        ],
        'clinic' => [
            'name' => 'Klinik',
            'description' => 'Açık ve ferah düzen, sağ tarafta görsel bulunan ikiye bölünmüş hero, güven rozetleri ve belirgin randevu butonu. Sağlık ve hizmet sektörleri için.',
            'font' => 'dm-sans',
            'primary_color' => '#0ea5e9',
            'suits' => ['Diş Kliniği', 'Sağlık', 'Güzellik', 'Eğitim'],
            'preview' => 'images/themes/clinic.png',
        ],
        'showcase' => [
            'name' => 'Vitrin',
            'description' => 'Tam ekran görsel hero üzerinde arama/filtre kutusu, şeffaf üst menü ve ürün/ilan odaklı geniş ızgaralar. Emlak, otomotiv ve ürün satan işletmeler için.',
            'font' => 'manrope',
            'primary_color' => '#e11d48',
            'suits' => ['Emlak', 'Lastik / Otomotiv', 'Mağaza', 'Kitapçı'],
            'preview' => 'images/themes/showcase.png',
        ],
    ],
];
