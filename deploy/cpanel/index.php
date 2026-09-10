<?php

/*
|--------------------------------------------------------------------------
| cPanel "public_html" ön yükleyicisi
|--------------------------------------------------------------------------
| Uygulama dosyaları public_html'in DIŞINDA (örn. ~/site) duruyorsa
| bu dosyayı ve yanındaki .htaccess'i public_html içine kopyalayın, ardından
| public/ klasöründeki build/, uploads/, favicon.ico dosyalarını da taşıyın.
| APP_DIR değerini kendi dizin adınıza göre düzenleyin.
*/

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$appDir = __DIR__.'/../site'; // <-- uygulama dizini

if (file_exists($maintenance = $appDir.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $appDir.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $appDir.'/bootstrap/app.php';

$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
