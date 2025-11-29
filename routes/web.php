<?php

use App\Http\Controllers\GigController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Models\Gig;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    $featuredGigs = Gig::with(['seller.user', 'category'])
        ->latest()
        ->take(6)
        ->get();

    return view('welcome', compact('featuredGigs'));
})->name('home');

// Order routes - BLOCKED FOR ADMIN
Route::middleware(['auth', 'no-admin'])->group(function () {
    // Order listing
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    // Create order for a gig
    Route::get('/gigs/{gig}/order', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/gigs/{gig}/order', [OrderController::class, 'store'])->name('orders.store');

    // Order details
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Seller actions
    Route::post('/orders/{order}/quote', [OrderController::class, 'quote'])->name('orders.quote');
    Route::post('/orders/{order}/deliver', [OrderController::class, 'deliver'])->name('orders.deliver');
    Route::post('/orders/{order}/complete-tutoring', [OrderController::class, 'completeTutoring'])->name('orders.complete-tutoring');

    // Buyer actions
    Route::post('/orders/{order}/accept', [OrderController::class, 'acceptQuote'])->name('orders.accept');
    Route::post('/orders/{order}/decline', [OrderController::class, 'declineQuote'])->name('orders.decline');
    Route::post('/orders/{order}/revision', [OrderController::class, 'requestRevision'])->name('orders.revision');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');

    // Both can do
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Gig routes - Public viewing, but create/edit/delete BLOCKED FOR ADMIN
Route::get('/gigs', [GigController::class, 'index'])->name('gigs.index');
// ini membuat conflict. pindah ke bawah
// Route::get('/gigs/{gig}', [GigController::class, 'show'])->name('gigs.show');
Route::get('/gigs-search-suggestions', [GigController::class, 'searchSuggestions'])->name('gigs.search.suggestions');

Route::middleware(['auth', 'no-admin'])->group(function () {
    Route::get('/gigs/create', [GigController::class, 'create'])->name('gigs.create');
    Route::post('/gigs', [GigController::class, 'store'])->name('gigs.store');
    Route::get('/gigs/{gig}/edit', [GigController::class, 'edit'])->name('gigs.edit');
    Route::put('/gigs/{gig}', [GigController::class, 'update'])->name('gigs.update');
    Route::patch('/gigs/{gig}', [GigController::class, 'update']);
    Route::delete('/gigs/{gig}', [GigController::class, 'destroy'])->name('gigs.destroy');
});

// kalau disimpan di atas, /create dianggap {gig}
Route::get('/gigs/{gig}', [GigController::class, 'show'])->name('gigs.show');

// Profile routes
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    Route::delete('/profile/banner', [ProfileController::class, 'removeBanner'])->name('profile.banner.remove');
});

// Seller routes - BLOCKED FOR ADMIN
Route::middleware(['auth', 'no-admin'])->group(function () {
    Route::get('/seller/register', [SellerController::class, 'create'])->name('seller.register');
    Route::post('/seller/register', [SellerController::class, 'store']);
    Route::get('/seller/status', [SellerController::class, 'status'])->name('seller.status');
    Route::get('/dashboard/seller', [SellerController::class, 'dashboard'])->name('seller.dashboard');
});



Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Manage Categories
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

    // Manage Users (WITHOUT promote/demote - admin only via seeder)
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // MANAGE SELLER REQUESTS
    Route::get('/seller-requests', [App\Http\Controllers\Admin\SellerRequestController::class, 'index'])->name('sellers.index');
    Route::get('/seller-requests/{id}', [App\Http\Controllers\Admin\SellerRequestController::class, 'show'])->name('sellers.show');
    Route::patch('/seller-requests/{id}/approve', [App\Http\Controllers\Admin\SellerRequestController::class, 'approve'])->name('sellers.approve');
    Route::post('/seller-requests/{id}/reject', [App\Http\Controllers\Admin\SellerRequestController::class, 'reject'])->name('sellers.reject');
    Route::post('/seller-requests/{id}/suspend', [App\Http\Controllers\Admin\SellerRequestController::class, 'suspend'])->name('sellers.suspend');
    Route::patch('/seller-requests/{id}/reactivate', [App\Http\Controllers\Admin\SellerRequestController::class, 'reactivate'])->name('sellers.reactivate');
});

require __DIR__ . '/auth.php';
