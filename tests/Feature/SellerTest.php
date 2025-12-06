<?php

/**
 * @mixin \Tests\TestCase
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */

use App\Models\Seller;

/** @var \Tests\TestCase $this */

describe('Seller Registration View', function () {

    it('requires authentication to view seller registration', function () {
        $response = $this->get('/seller/register');

        $response->assertRedirect('/login');
    });

    it('shows seller registration form to non-sellers', function () {
        $user = createBuyer();

        $response = $this->actingAs($user)->get('/seller/register');

        $response->assertStatus(200);
        $response->assertViewIs('seller.register');
    });

    it('redirects existing sellers to dashboard', function () {
        ['user' => $user] = createSeller();

        $response = $this->actingAs($user)->get('/seller/register');

        $response->assertRedirect();
    });

    it('blocks admin from seller registration', function () {
        $admin = createAdmin();

        $response = $this->actingAs($admin)->get('/seller/register');

        $response->assertRedirect();
    });
});

describe('Seller Registration Submit', function () {

    it('allows user to register as seller', function () {
        $user = createBuyer();

        $response = $this->actingAs($user)->post('/seller/register', [
            'business_name' => 'My Awesome Services',
            'description' => 'I provide top quality web development services',
            'major' => 'Computer Science',
            'university' => 'Test University',
            'portfolio_url' => 'https://example.com/portfolio',
            'skills' => ['PHP', 'Laravel', 'Vue.js'],
        ]);

        $response->assertRedirect(route('seller.status'));
        $this->assertDatabaseHas('sellers', [
            'user_id' => $user->id,
            'business_name' => 'My Awesome Services',
            'status' => Seller::STATUS_PENDING,
        ]);
    });

    it('validates required fields', function () {
        $user = createBuyer();

        $response = $this->actingAs($user)->post('/seller/register', []);

        $response->assertSessionHasErrors(['business_name', 'description', 'major', 'university', 'skills']);
    });
});

describe('Seller Status Page', function () {

    it('shows pending status', function () {
        $user = createBuyer();
        Seller::create([
            'user_id' => $user->id,
            'business_name' => 'Test Business',
            'description' => 'Test description',
            'major' => 'CS',
            'university' => 'Test Uni',
            'skills' => ['PHP'],
            'status' => Seller::STATUS_PENDING,
        ]);

        $response = $this->actingAs($user)->get('/seller/status');

        $response->assertStatus(200);
        $response->assertViewIs('seller.status');
    });
});

describe('Seller Dashboard', function () {

    it('shows dashboard to approved sellers', function () {
        ['user' => $user] = createSeller();

        $response = $this->actingAs($user)->get('/dashboard/seller');

        $response->assertStatus(200);
        $response->assertViewIs('seller.dashboard');
    });

    it('redirects non-sellers away from dashboard', function () {
        $user = createBuyer();

        $response = $this->actingAs($user)->get('/dashboard/seller');

        $response->assertRedirect();
    });

    it('shows seller gigs on dashboard', function () {
        ['user' => $user, 'seller' => $seller] = createSeller();
        $gig = createGig($seller);

        $response = $this->actingAs($user)->get('/dashboard/seller');

        $response->assertStatus(200);
        $response->assertSee($gig->title);
    });
});
