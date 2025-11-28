<?php

use App\Http\Controllers\GigController;
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

// Seller routes
Route::middleware('auth')->group(function () {
    Route::get('/seller/register', [SellerController::class, 'create'])->name('seller.register');
    Route::post('/seller/register', [SellerController::class, 'store']);
    Route::get('/dashboard/seller', [SellerController::class, 'dashboard'])->name('seller.dashboard');
});

require __DIR__ . '/auth.php';
