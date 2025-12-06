<?php

/**
 * @mixin \Tests\TestCase
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */

use App\Models\Gig;

/** @var \Tests\TestCase $this */

describe('Gig CRUD - Create', function () {

    it('requires authentication to create a gig', function () {
        $response = $this->get('/gigs/create');

        $response->assertRedirect('/login');
    });

    it('requires seller status to create a gig', function () {
        $user = createBuyer();

        $response = $this->actingAs($user)->get('/gigs/create');

        $response->assertRedirect();
    });

    it('shows create gig form to sellers', function () {
        ['user' => $user] = createSeller();

        $response = $this->actingAs($user)->get('/gigs/create');

        $response->assertStatus(200);
        $response->assertViewIs('gigs.create');
    });

    it('allows seller to create a gig', function () {
        ['user' => $user, 'seller' => $seller] = createSeller();
        $category = createCategory();

        $response = $this->actingAs($user)->post('/gigs', [
            'title' => 'New Test Gig',
            'description' => 'This is a test gig description with enough content',
            'category_id' => $category->id,
            'min_price' => 100000,
            'max_price' => 200000,
            'delivery_days' => 5,
            'allows_tutoring' => true,
        ]);

        $response->assertRedirect(route('seller.dashboard'));
        $this->assertDatabaseHas('gigs', [
            'seller_id' => $seller->id,
            'title' => 'New Test Gig',
        ]);
    });

    it('validates required fields when creating a gig', function () {
        ['user' => $user] = createSeller();

        $response = $this->actingAs($user)->post('/gigs', []);

        $response->assertSessionHasErrors(['title', 'description', 'category_id', 'min_price', 'delivery_days']);
    });

    it('blocks admin from creating gigs', function () {
        $admin = createAdmin();

        $response = $this->actingAs($admin)->get('/gigs/create');

        $response->assertRedirect();
    });
});

describe('Gig CRUD - Update', function () {

    it('shows edit form to gig owner', function () {
        ['user' => $user, 'seller' => $seller] = createSeller();
        $gig = createGig($seller);

        $response = $this->actingAs($user)->get('/gigs/' . $gig->id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('gigs.edit');
    });

    it('prevents non-owner from editing gig', function () {
        $gig = createGig();
        ['user' => $otherSellerUser] = createSeller(); // Another seller, not owner

        $response = $this->actingAs($otherSellerUser)->get('/gigs/' . $gig->id . '/edit');

        $response->assertStatus(403);
    });

    it('allows owner to update gig', function () {
        ['user' => $user, 'seller' => $seller] = createSeller();
        $gig = createGig($seller);

        $response = $this->actingAs($user)->put('/gigs/' . $gig->id, [
            'title' => 'Updated Gig Title',
            'description' => $gig->description,
            'category_id' => $gig->category_id,
            'min_price' => $gig->min_price,
            'delivery_days' => $gig->delivery_days,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('gigs', [
            'id' => $gig->id,
            'title' => 'Updated Gig Title',
        ]);
    });
});

describe('Gig CRUD - Delete', function () {

    it('allows owner to delete gig', function () {
        ['user' => $user, 'seller' => $seller] = createSeller();
        $gig = createGig($seller);
        $gigId = $gig->id;

        $response = $this->actingAs($user)->delete('/gigs/' . $gig->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('gigs', ['id' => $gigId]);
    });

    it('prevents non-owner from deleting gig', function () {
        $gig = createGig();
        ['user' => $otherSellerUser] = createSeller(); // Another seller, not owner

        $response = $this->actingAs($otherSellerUser)->delete('/gigs/' . $gig->id);

        $response->assertStatus(403);
    });
});
