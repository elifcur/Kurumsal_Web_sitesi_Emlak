<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Public\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Sector-specific routes live in templates/{key}/routes.php and are loaded by
| the TemplateServiceProvider. Core sections are only registered when the
| active template supports them.
*/

$template = template();

Route::get('/', [HomeController::class, 'index'])->name('home');

if ($template->supports('services')) {
    Route::get('/hizmetler', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/hizmetler/{slug}', [ServiceController::class, 'show'])->name('services.show');
}

if ($template->supports('products')) {
    Route::get('/urunler', [ProductController::class, 'index'])->name('products.index');
    Route::get('/urunler/{slug}', [ProductController::class, 'show'])->name('products.show');
}

if ($template->supports('blog')) {
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
}

if ($template->supports('team')) {
    Route::get('/ekibimiz', [TeamController::class, 'index'])->name('team.index');
    Route::get('/ekibimiz/{slug}', [TeamController::class, 'show'])->name('team.show');
}

Route::get('/iletisim', [ContactController::class, 'show'])->name('contact.show');
Route::post('/iletisim', [ContactController::class, 'store'])->middleware('throttle:contact')->name('contact.store');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Dynamic CMS pages (must stay last).
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '(?!admin$)[a-z0-9\-]+')
    ->name('page.show');
