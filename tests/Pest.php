<?php

/**
 * @see https://pestphp.com/docs/configuring-tests
 *
 * This file is used to configure Pest for the test suite.
 * The uses() call binds TestCase and RefreshDatabase to all tests.
 *
 * @mixin \Tests\TestCase
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */

use Illuminate\Foundation\Testing\RefreshDatabase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

uses(
    Tests\TestCase::class,
    RefreshDatabase::class,
)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

function createUser(array $attributes = []): \App\Models\User
{
    return \App\Models\User::factory()->create($attributes);
}

function createAdmin(): \App\Models\User
{
    return \App\Models\User::factory()->create(['role' => 'admin']);
}

function createBuyer(): \App\Models\User
{
    return \App\Models\User::factory()->create(['is_seller' => false]);
}

function createSeller(): array
{
    $user = \App\Models\User::factory()->create(['is_seller' => true]);
    $seller = \App\Models\Seller::create([
        'user_id' => $user->id,
        'business_name' => 'Test Business ' . uniqid(),
        'description' => 'Test seller description',
        'major' => 'Computer Science',
        'university' => 'Test University',
        'portfolio_url' => 'https://example.com/portfolio',
        'skills' => ['PHP', 'Laravel', 'JavaScript'],
        'is_active' => true,
        'status' => \App\Models\Seller::STATUS_APPROVED,
    ]);

    return ['user' => $user, 'seller' => $seller];
}

function createCategory(): \App\Models\Category
{
    return \App\Models\Category::create([
        'name' => 'Test Category ' . uniqid(),
        'slug' => 'test-category-' . uniqid(),
        'description' => 'Test category description',
    ]);
}

function createGig(?\App\Models\Seller $seller = null, ?\App\Models\Category $category = null): \App\Models\Gig
{
    if (!$seller) {
        ['seller' => $seller] = createSeller();
    }
    if (!$category) {
        $category = createCategory();
    }

    return \App\Models\Gig::create([
        'seller_id' => $seller->id,
        'category_id' => $category->id,
        'title' => 'Test Gig ' . uniqid(),
        'description' => 'Test gig description with enough content',
        'min_price' => 50000,
        'max_price' => 150000,
        'delivery_days' => 3,
        'allows_tutoring' => true,
    ]);
}

function createOrder(
    ?\App\Models\User $buyer = null,
    ?\App\Models\Gig $gig = null,
    string $status = \App\Models\Order::STATUS_PENDING
): \App\Models\Order {
    if (!$buyer) {
        $buyer = createBuyer();
    }
    if (!$gig) {
        $gig = createGig();
    }

    return \App\Models\Order::create([
        'buyer_id' => $buyer->id,
        'seller_id' => $gig->seller->user_id,
        'gig_id' => $gig->id,
        'type' => \App\Models\Order::TYPE_STANDARD,
        'requirements' => 'Test requirements for the order',
        'status' => $status,
        'price' => $status !== \App\Models\Order::STATUS_PENDING ? 100000 : null,
        'delivery_days' => $status !== \App\Models\Order::STATUS_PENDING ? 3 : null,
    ]);
}
