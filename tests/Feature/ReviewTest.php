<?php

/**
 * @mixin \Tests\TestCase
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */

use App\Models\Review;
use App\Models\Order;

/** @var \Tests\TestCase $this */

describe('Review Creation', function () {

    it('allows buyer to review completed order', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_COMPLETED);
        $order->update(['completed_at' => now()]);

        $response = $this->actingAs($buyer)->post('/orders/' . $order->id . '/review', [
            'rating' => 5,
            'comment' => 'Excellent work! Very satisfied.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'rating' => 5,
        ]);
    });

    it('prevents review on non-completed order', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_PENDING);

        $response = $this->actingAs($buyer)->post('/orders/' . $order->id . '/review', [
            'rating' => 5,
            'comment' => 'Test review',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    });

    it('prevents non-buyer from reviewing', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_COMPLETED);
        $order->update(['completed_at' => now()]);
        $otherUser = createBuyer();

        $response = $this->actingAs($otherUser)->post('/orders/' . $order->id . '/review', [
            'rating' => 5,
            'comment' => 'Test review',
        ]);

        $response->assertStatus(403);
    });

    it('prevents duplicate review', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_COMPLETED);
        $order->update(['completed_at' => now()]);

        // Create first review
        Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $order->gig->seller_id,
            'gig_id' => $order->gig_id,
            'rating' => 4,
            'comment' => 'First review',
        ]);

        // Try to create second review
        $response = $this->actingAs($buyer)->post('/orders/' . $order->id . '/review', [
            'rating' => 5,
            'comment' => 'Second review attempt',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    });

    it('validates rating range', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_COMPLETED);
        $order->update(['completed_at' => now()]);

        $response = $this->actingAs($buyer)->post('/orders/' . $order->id . '/review', [
            'rating' => 6, // invalid
            'comment' => 'Test review',
        ]);

        $response->assertSessionHasErrors('rating');
    });
});

describe('Review Update', function () {

    it('allows buyer to update their review', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_COMPLETED);
        $order->update(['completed_at' => now()]);

        // Create initial review
        Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $order->gig->seller_id,
            'gig_id' => $order->gig_id,
            'rating' => 4,
            'comment' => 'Initial review',
        ]);

        $response = $this->actingAs($buyer)->put('/orders/' . $order->id . '/review', [
            'rating' => 5,
            'comment' => 'Updated review',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Updated review',
        ]);
    });

    it('prevents non-buyer from updating review', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_COMPLETED);
        $order->update(['completed_at' => now()]);

        Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $order->gig->seller_id,
            'gig_id' => $order->gig_id,
            'rating' => 4,
            'comment' => 'Initial review',
        ]);

        $otherUser = createBuyer();

        $response = $this->actingAs($otherUser)->put('/orders/' . $order->id . '/review', [
            'rating' => 1,
            'comment' => 'Trying to sabotage',
        ]);

        $response->assertStatus(403);
    });
});

describe('Review Display', function () {

    it('shows reviews on gig page', function () {
        $buyer = createBuyer();
        $gig = createGig();
        $order = createOrder($buyer, $gig, Order::STATUS_COMPLETED);
        $order->update(['completed_at' => now()]);

        Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $gig->seller_id,
            'gig_id' => $gig->id,
            'rating' => 5,
            'comment' => 'Amazing work on this gig!',
        ]);

        $response = $this->get('/gigs/' . $gig->id);

        $response->assertStatus(200);
        $response->assertSee('Amazing work on this gig!');
        $response->assertSee($buyer->name);
    });

    it('shows average rating on gig page', function () {
        $gig = createGig();

        // Create multiple reviews
        for ($i = 1; $i <= 3; $i++) {
            $buyer = createBuyer();
            $order = createOrder($buyer, $gig, Order::STATUS_COMPLETED);
            $order->update(['completed_at' => now()]);

            Review::create([
                'order_id' => $order->id,
                'reviewer_id' => $buyer->id,
                'seller_id' => $gig->seller_id,
                'gig_id' => $gig->id,
                'rating' => 4,
                'comment' => 'Review ' . $i,
            ]);
        }

        $response = $this->get('/gigs/' . $gig->id);

        $response->assertStatus(200);
        $response->assertSee('4'); // average rating
        $response->assertSee('3 ulasan'); // review count
    });
});
