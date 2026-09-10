<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Active Template
    |--------------------------------------------------------------------------
    |
    | Every deployment runs exactly one sector template. The key must match a
    | directory under the templates path (e.g. "corporate", "real-estate"). The
    | template's class is resolved by convention as Templates\{Studly}\Template.
    |
    */

    'active' => env('APP_TEMPLATE', 'corporate'),

    'path' => base_path('templates'),

];
