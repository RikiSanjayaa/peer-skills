<?php

/**
 * @mixin \Tests\TestCase
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */

use App\Models\Gig;
use App\Models\Category;

/** @var \Tests\TestCase $this */

describe('Public Pages', function () {

    it('shows the home page', function () {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    });

    it('shows the home page with featured gigs', function () {
        $gig = createGig();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($gig->title);
    });
});

describe('Gig Listing', function () {

    it('shows the gigs index page', function () {
        $response = $this->get('/gigs');

        $response->assertStatus(200);
        $response->assertViewIs('gigs.index');
    });

    it('lists all gigs', function () {
        $gig1 = createGig();
        $gig2 = createGig();

        $response = $this->get('/gigs');

        $response->assertStatus(200);
        $response->assertSee($gig1->title);
        $response->assertSee($gig2->title);
    });

    it('filters gigs by category', function () {
        $category1 = createCategory();
        $category2 = createCategory();

        ['seller' => $seller] = createSeller();
        $gig1 = createGig($seller, $category1);
        $gig2 = createGig($seller, $category2);

        $response = $this->get('/gigs?category=' . $category1->slug);

        $response->assertStatus(200);
        $response->assertSee($gig1->title);
        $response->assertDontSee($gig2->title);
    });

    it('searches gigs by keyword', function () {
        ['seller' => $seller] = createSeller();
        $gig1 = Gig::create([
            'seller_id' => $seller->id,
            'category_id' => createCategory()->id,
            'title' => 'Laravel Development Service',
            'description' => 'I will create Laravel apps',
            'min_price' => 50000,
            'delivery_days' => 3,
        ]);
        $gig2 = createGig($seller);

        $response = $this->get('/gigs?search=Laravel');

        $response->assertStatus(200);
        $response->assertSee('Laravel Development Service');
    });
});

describe('Gig Details', function () {

    it('shows a gig details page', function () {
        $gig = createGig();

        $response = $this->get('/gigs/' . $gig->id);

        $response->assertStatus(200);
        $response->assertViewIs('gigs.show');
        $response->assertSee($gig->title);
        $response->assertSee($gig->description);
    });

    it('shows seller info on gig page', function () {
        $gig = createGig();

        $response = $this->get('/gigs/' . $gig->id);

        $response->assertStatus(200);
        $response->assertSee($gig->seller->business_name);
    });

    it('shows order button for authenticated non-owner', function () {
        $gig = createGig();
        $buyer = createBuyer();

        $response = $this->actingAs($buyer)->get('/gigs/' . $gig->id);

        $response->assertStatus(200);
        $response->assertSee('Pesan Sekarang');
    });

    it('shows login prompt for guests', function () {
        $gig = createGig();

        $response = $this->get('/gigs/' . $gig->id);

        $response->assertStatus(200);
        $response->assertSee('Masuk untuk Memesan');
    });

    it('does not show order button to gig owner', function () {
        ['user' => $user, 'seller' => $seller] = createSeller();
        $gig = createGig($seller);

        $response = $this->actingAs($user)->get('/gigs/' . $gig->id);

        $response->assertStatus(200);
        $response->assertSee('Ini adalah gig Anda');
    });

    it('returns 404 for non-existent gig', function () {
        $response = $this->get('/gigs/99999');

        $response->assertStatus(404);
    });
});
