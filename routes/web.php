<?php

use App\Http\Controllers\GigController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Models\Gig;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredGigs = Gig::with(['seller.user', 'category'])
        ->latest()
        ->take(6)
        ->get();

    return view('welcome', compact('featuredGigs'));
})->name('home');

// Gig routes
Route::resource('gigs', GigController::class);
Route::get('/gigs-search-suggestions', [GigController::class, 'searchSuggestions'])->name('gigs.search.suggestions');

// Profile routes
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    Route::delete('/profile/banner', [ProfileController::class, 'removeBanner'])->name('profile.banner.remove');
});

// Seller routes
Route::middleware('auth')->group(function () {
    Route::get('/seller/register', [SellerController::class, 'create'])->name('seller.register');
    Route::post('/seller/register', [SellerController::class, 'store']);
    Route::get('/dashboard/seller', [SellerController::class, 'dashboard'])->name('seller.dashboard');
});

require __DIR__ . '/auth.php';
