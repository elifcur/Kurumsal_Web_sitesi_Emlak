<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordResetController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes (prefix: /admin, name: admin.)
|--------------------------------------------------------------------------
*/

$template = template();

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:login')->name('login.store');

    Route::get('/sifremi-unuttum', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/sifremi-unuttum', [PasswordResetController::class, 'email'])->middleware('throttle:password-reset')->name('password.email');
    Route::get('/sifre-sifirla/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/sifre-sifirla', [PasswordResetController::class, 'update'])->middleware('throttle:password-reset')->name('password.update');
});

Route::middleware(['auth', 'admin'])->group(function () use ($template): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/sifre', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::middleware('can:manage-pages')->group(function (): void {
        Route::resource('pages', PageController::class)->except('show');
    });

    if ($template->supports('services')) {
        Route::middleware('can:manage-services')->group(function (): void {
            Route::resource('services', ServiceController::class)->except('show');
            Route::post('services/reorder', [ServiceController::class, 'reorder'])->name('services.reorder');
        });
    }

    if ($template->supports('products')) {
        Route::middleware('can:manage-products')->group(function (): void {
            Route::resource('products', ProductController::class)->except('show');
        });
    }

    if ($template->supports('products') || $template->supports('blog')) {
        Route::middleware('can:manage-products')->group(function (): void {
            Route::resource('categories', CategoryController::class)->except('show');
        });
    }

    if ($template->supports('blog')) {
        Route::middleware('can:manage-posts')->group(function (): void {
            Route::resource('posts', PostController::class)->except('show');
        });
    }

    Route::middleware('can:manage-extras')->group(function () use ($template): void {
        if ($template->supports('team')) {
            Route::resource('team', TeamMemberController::class)->except('show')->parameters(['team' => 'member']);
            Route::post('team/reorder', [TeamMemberController::class, 'reorder'])->name('team.reorder');
        }

        if ($template->supports('testimonials')) {
            Route::resource('testimonials', TestimonialController::class)->except('show');
        }

        if ($template->supports('faqs')) {
            Route::resource('faqs', FaqController::class)->except('show');
            Route::post('faqs/reorder', [FaqController::class, 'reorder'])->name('faqs.reorder');
        }

        if ($template->supports('partners')) {
            Route::resource('partners', PartnerController::class)->except('show');
        }
    });

    Route::middleware('can:manage-media')->group(function (): void {
        Route::get('media', [MediaController::class, 'index'])->name('media.index');
        Route::get('media/picker', [MediaController::class, 'picker'])->name('media.picker');
        Route::post('media', [MediaController::class, 'store'])->name('media.store');
        Route::put('media/{medium}', [MediaController::class, 'update'])->name('media.update');
        Route::delete('media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');
    });

    Route::middleware('can:manage-messages')->group(function (): void {
        Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
        Route::patch('messages/{message}/toggle-read', [ContactMessageController::class, 'toggleRead'])->name('messages.toggle-read');
        Route::delete('messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');
        Route::post('messages/bulk', [ContactMessageController::class, 'bulk'])->name('messages.bulk');
    });

    Route::middleware('can:manage-settings')->group(function (): void {
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/clear-cache', [SettingController::class, 'clearCache'])->name('settings.clear-cache');
    });

    Route::middleware('can:manage-users')->group(function (): void {
        Route::resource('users', UserController::class)->except('show');
    });
});
