<?php

use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Seller routes
Route::middleware('auth')->group(function () {
    Route::get('/seller/register', [SellerController::class, 'create'])->name('seller.register');
    Route::post('/seller/register', [SellerController::class, 'store']);
    Route::get('/dashboard/seller', [SellerController::class, 'dashboard'])->name('seller.dashboard');
});

require __DIR__ . '/auth.php';
