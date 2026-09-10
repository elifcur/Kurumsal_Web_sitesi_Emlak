<?php

use Illuminate\Support\Facades\Route;
use Templates\RealEstate\Http\Controllers\Admin\ListingController as AdminListingController;
use Templates\RealEstate\Http\Controllers\Public\ListingController;

Route::get('/ilanlar', [ListingController::class, 'index'])->name('listings.index');
Route::get('/ilanlar/{slug}', [ListingController::class, 'show'])->name('listings.show');
Route::post('/ilanlar/{slug}/talep', [ListingController::class, 'inquiry'])->middleware('throttle:contact')->name('listings.inquiry');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin', 'can:manage-sector'])->group(function (): void {
    Route::resource('listings', AdminListingController::class)->except('show');
});
