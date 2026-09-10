<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Active Template
    |--------------------------------------------------------------------------
    |
    | Every deployment runs exactly one sector template. The key must match a
    | directory under the templates path (e.g. "real-estate", "clinic"). The
    | template's class is resolved by convention as Templates\{Studly}\Template.
    |
    */

    'active' => env('APP_TEMPLATE', 'real-estate'),

    'path' => base_path('templates'),

];
