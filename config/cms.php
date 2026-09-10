<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Role Permissions
    |--------------------------------------------------------------------------
    |
    | The super_admin role implicitly has every permission. Other roles only
    | receive the abilities listed here. Abilities are checked via Gates.
    |
    */

    'permissions' => [
        'admin' => [
            'manage-pages', 'manage-services', 'manage-products', 'manage-posts', 'manage-extras',
            'manage-media', 'manage-messages', 'manage-settings', 'manage-sector',
        ],
        'editor' => [
            'manage-pages', 'manage-services', 'manage-products', 'manage-posts', 'manage-extras',
            'manage-media', 'manage-messages', 'manage-sector',
        ],
    ],

    'all_permissions' => [
        'manage-pages' => 'Sayfalar',
        'manage-services' => 'Hizmetler',
        'manage-products' => 'Ürünler',
        'manage-posts' => 'Blog',
        'manage-media' => 'Medya',
        'manage-messages' => 'Mesajlar',
        'manage-settings' => 'Ayarlar',
        'manage-extras' => 'Ekip, Referanslar, SSS, Partnerler',
        'manage-sector' => 'Sektör İçerikleri',
        'manage-users' => 'Kullanıcılar',
    ],

    /*
    |--------------------------------------------------------------------------
    | Uploads
    |--------------------------------------------------------------------------
    */

    'uploads' => [
        'disk' => 'uploads',
        'max_kb' => (int) env('CMS_UPLOAD_MAX_KB', 5120),
        'image_mimes' => ['jpg', 'jpeg', 'png', 'webp', 'gif'],
        'document_mimes' => ['pdf'],
        'max_width' => 1920,
        'thumb_width' => 400,
    ],

    'page_templates' => [
        'default' => 'Standart',
        'wide' => 'Geniş (kenar boşluksuz)',
        'contact' => 'İletişim',
    ],

    'message_types' => [
        'contact' => 'İletişim',
    ],

    'pagination' => [
        'admin' => 20,
        'public' => 12,
    ],
];
