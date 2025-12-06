<?php

use App\Models\Order;
use App\Models\Review;

describe('Order Model', function () {

    it('belongs to buyer', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer);

        expect($order->buyer)->toBeInstanceOf(\App\Models\User::class);
        expect($order->buyer->id)->toBe($buyer->id);
    });

    it('belongs to seller', function () {
        $order = createOrder();

        expect($order->seller)->toBeInstanceOf(\App\Models\User::class);
    });

    it('belongs to gig', function () {
        $order = createOrder();

        expect($order->gig)->toBeInstanceOf(\App\Models\Gig::class);
    });

    it('has one review', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_COMPLETED);

        Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $order->gig->seller_id,
            'gig_id' => $order->gig_id,
            'rating' => 5,
        ]);

        expect($order->review)->toBeInstanceOf(Review::class);
    });

    it('checks if user is buyer', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer);

        expect($order->isBuyer($buyer))->toBeTrue();
        expect($order->isBuyer(createBuyer()))->toBeFalse();
    });

    it('checks if user is seller', function () {
        $gig = createGig();
        $order = createOrder(null, $gig);

        expect($order->isSeller($gig->seller->user))->toBeTrue();
        expect($order->isSeller(createBuyer()))->toBeFalse();
    });

    it('checks if user is participant', function () {
        $buyer = createBuyer();
        $gig = createGig();
        $order = createOrder($buyer, $gig);

        expect($order->isParticipant($buyer))->toBeTrue();
        expect($order->isParticipant($gig->seller->user))->toBeTrue();
        expect($order->isParticipant(createBuyer()))->toBeFalse();
    });

    it('checks if order is tutoring type', function () {
        $order = createOrder();
        expect($order->isTutoring())->toBeFalse();

        $order->update(['type' => Order::TYPE_TUTORING]);
        expect($order->isTutoring())->toBeTrue();
    });

    it('has status constants', function () {
        expect(Order::STATUS_PENDING)->toBe('pending');
        expect(Order::STATUS_QUOTED)->toBe('quoted');
        expect(Order::STATUS_ACCEPTED)->toBe('accepted');
        expect(Order::STATUS_DELIVERED)->toBe('delivered');
        expect(Order::STATUS_COMPLETED)->toBe('completed');
        expect(Order::STATUS_CANCELLED)->toBe('cancelled');
    });
});

describe('Order State Checks', function () {

    it('canBeQuoted when pending', function () {
        $order = createOrder(null, null, Order::STATUS_PENDING);
        expect($order->canBeQuoted())->toBeTrue();

        $order->update(['status' => Order::STATUS_QUOTED]);
        expect($order->canBeQuoted())->toBeFalse();
    });

    it('canBeAccepted when quoted', function () {
        $order = createOrder(null, null, Order::STATUS_QUOTED);
        expect($order->canBeAccepted())->toBeTrue();

        $order->update(['status' => Order::STATUS_PENDING]);
        expect($order->canBeAccepted())->toBeFalse();
    });
});

describe('Review Model', function () {

    it('belongs to order', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_COMPLETED);

        $review = Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $order->gig->seller_id,
            'gig_id' => $order->gig_id,
            'rating' => 5,
            'comment' => 'Great!',
        ]);

        expect($review->order)->toBeInstanceOf(Order::class);
        expect($review->order->id)->toBe($order->id);
    });

    it('belongs to reviewer', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_COMPLETED);

        $review = Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $order->gig->seller_id,
            'gig_id' => $order->gig_id,
            'rating' => 5,
        ]);

        expect($review->reviewer)->toBeInstanceOf(\App\Models\User::class);
        expect($review->reviewer->id)->toBe($buyer->id);
    });

    it('belongs to seller', function () {
        $buyer = createBuyer();
        $gig = createGig();
        $order = createOrder($buyer, $gig, Order::STATUS_COMPLETED);

        $review = Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $gig->seller_id,
            'gig_id' => $gig->id,
            'rating' => 4,
        ]);

        expect($review->seller)->toBeInstanceOf(\App\Models\Seller::class);
        expect($review->seller->id)->toBe($gig->seller_id);
    });

    it('belongs to gig', function () {
        $buyer = createBuyer();
        $gig = createGig();
        $order = createOrder($buyer, $gig, Order::STATUS_COMPLETED);

        $review = Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $gig->seller_id,
            'gig_id' => $gig->id,
            'rating' => 4,
        ]);

        expect($review->gig)->toBeInstanceOf(\App\Models\Gig::class);
        expect($review->gig->id)->toBe($gig->id);
    });

    it('casts rating to integer', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer, null, Order::STATUS_COMPLETED);

        $review = Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $order->gig->seller_id,
            'gig_id' => $order->gig_id,
            'rating' => '5',
        ]);

        expect($review->rating)->toBeInt();
    });
});
