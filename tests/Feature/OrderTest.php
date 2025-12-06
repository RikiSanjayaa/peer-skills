<?php

/**
 * @mixin \Tests\TestCase
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */

use App\Models\Order;

/** @var \Tests\TestCase $this */

describe('Order Creation', function () {

    it('requires authentication to create an order', function () {
        $gig = createGig();

        $response = $this->get('/gigs/' . $gig->id . '/order');

        $response->assertRedirect('/login');
    });

    it('shows order creation form', function () {
        $gig = createGig();
        $buyer = createBuyer();

        $response = $this->actingAs($buyer)->get('/gigs/' . $gig->id . '/order');

        $response->assertStatus(200);
        $response->assertViewIs('orders.create');
    });

    it('prevents seller from ordering own gig', function () {
        ['user' => $user, 'seller' => $seller] = createSeller();
        $gig = createGig($seller);

        $response = $this->actingAs($user)->get('/gigs/' . $gig->id . '/order');

        $response->assertRedirect();
    });

    it('allows buyer to create an order', function () {
        $gig = createGig();
        $buyer = createBuyer();

        $response = $this->actingAs($buyer)->post('/gigs/' . $gig->id . '/order', [
            'requirements' => 'I need a website built with these specifications and more details here...',
            'type' => 'standard',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'buyer_id' => $buyer->id,
            'gig_id' => $gig->id,
            'status' => Order::STATUS_PENDING,
        ]);
    });

    it('validates requirements when creating order', function () {
        $gig = createGig();
        $buyer = createBuyer();

        $response = $this->actingAs($buyer)->post('/gigs/' . $gig->id . '/order', [
            'requirements' => '', // empty
        ]);

        $response->assertSessionHasErrors('requirements');
    });

    it('blocks admin from creating orders', function () {
        $gig = createGig();
        $admin = createAdmin();

        $response = $this->actingAs($admin)->get('/gigs/' . $gig->id . '/order');

        $response->assertRedirect();
    });
});

describe('Order Viewing', function () {

    it('shows order details to buyer', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer);

        $response = $this->actingAs($buyer)->get('/orders/' . $order->id);

        $response->assertStatus(200);
        $response->assertViewIs('orders.show');
    });

    it('shows order details to seller', function () {
        $gig = createGig();
        $order = createOrder(null, $gig);

        $response = $this->actingAs($gig->seller->user)->get('/orders/' . $order->id);

        $response->assertStatus(200);
        $response->assertViewIs('orders.show');
    });

    it('prevents unauthorized users from viewing order', function () {
        $order = createOrder();
        $otherUser = createBuyer();

        $response = $this->actingAs($otherUser)->get('/orders/' . $order->id);

        $response->assertStatus(403);
    });

    it('shows order list to authenticated user', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer);

        $response = $this->actingAs($buyer)->get('/orders');

        $response->assertStatus(200);
        $response->assertViewIs('orders.index');
    });
});

describe('Order Actions - Seller', function () {

    it('allows seller to send quote', function () {
        $gig = createGig();
        $order = createOrder(null, $gig, Order::STATUS_PENDING);

        $response = $this->actingAs($gig->seller->user)->post('/orders/' . $order->id . '/quote', [
            'price' => 150000,
            'delivery_days' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_QUOTED,
            'price' => 150000,
        ]);
    });

    it('allows seller to deliver order', function () {
        $gig = createGig();
        $order = createOrder(null, $gig, Order::STATUS_ACCEPTED);
        $order->update(['price' => 100000, 'accepted_at' => now()]);

        $response = $this->actingAs($gig->seller->user)->post('/orders/' . $order->id . '/deliver', [
            'message' => 'Here is your completed work!',
        ]);

        $response->assertRedirect();
        $order->refresh();
        expect($order->status)->toBe(Order::STATUS_DELIVERED);
    });
});

describe('Order Actions - Buyer', function () {

    it('allows buyer to accept quote', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_QUOTED);
        $order->update(['price' => 100000, 'quoted_at' => now()]);

        $response = $this->actingAs($buyer)->post('/orders/' . $order->id . '/accept');

        $response->assertRedirect();
        $order->refresh();
        expect($order->status)->toBe(Order::STATUS_ACCEPTED);
    });

    it('allows buyer to decline quote', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_QUOTED);
        $order->update(['price' => 100000, 'quoted_at' => now()]);

        $response = $this->actingAs($buyer)->post('/orders/' . $order->id . '/decline');

        $response->assertRedirect();
        $order->refresh();
        expect($order->status)->toBe(Order::STATUS_DECLINED);
    });

    it('allows buyer to complete order', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_DELIVERED);
        $order->update(['price' => 100000, 'delivered_at' => now()]);

        $response = $this->actingAs($buyer)->post('/orders/' . $order->id . '/complete');

        $response->assertRedirect();
        $order->refresh();
        expect($order->status)->toBe(Order::STATUS_COMPLETED);
    });

    it('allows buyer to request revision', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_DELIVERED);
        $order->update(['price' => 100000, 'delivered_at' => now()]);

        $response = $this->actingAs($buyer)->post('/orders/' . $order->id . '/revision', [
            'message' => 'Please fix the following issues and make these changes...',
        ]);

        $response->assertRedirect();
        $order->refresh();
        expect($order->status)->toBe(Order::STATUS_REVISION_REQUESTED);
    });
});

describe('Order Cancellation', function () {

    it('allows buyer to cancel pending order', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_PENDING);

        $response = $this->actingAs($buyer)->post('/orders/' . $order->id . '/cancel');

        $response->assertRedirect();
        $order->refresh();
        expect($order->status)->toBe(Order::STATUS_CANCELLED);
    });

    it('allows seller to cancel pending order', function () {
        $gig = createGig();
        $order = createOrder(null, $gig, Order::STATUS_PENDING);

        $response = $this->actingAs($gig->seller->user)->post('/orders/' . $order->id . '/cancel');

        $response->assertRedirect();
        $order->refresh();
        expect($order->status)->toBe(Order::STATUS_CANCELLED);
    });
});
