<?php

use App\Models\User;
use App\Models\Seller;
use App\Models\Order;

describe('User Model', function () {

    it('has seller relationship', function () {
        ['user' => $user, 'seller' => $seller] = createSeller();

        expect($user->seller)->toBeInstanceOf(Seller::class);
        expect($user->seller->id)->toBe($seller->id);
    });

    it('has orders as buyer relationship', function () {
        $buyer = createBuyer();
        $order = createOrder($buyer);

        expect($buyer->buyerOrders)->toHaveCount(1);
        expect($buyer->buyerOrders->first()->id)->toBe($order->id);
    });

    it('casts is_seller to boolean', function () {
        $user = createUser(['is_seller' => 1]);

        expect($user->is_seller)->toBeBool();
        expect($user->is_seller)->toBeTrue();
    });

    it('casts social_links to array', function () {
        $user = createUser(['social_links' => ['twitter' => '@test']]);

        expect($user->social_links)->toBeArray();
    });
});

describe('Seller Model', function () {

    it('belongs to user', function () {
        ['user' => $user, 'seller' => $seller] = createSeller();

        expect($seller->user)->toBeInstanceOf(User::class);
        expect($seller->user->id)->toBe($user->id);
    });

    it('has many gigs', function () {
        ['seller' => $seller] = createSeller();
        $gig = createGig($seller);

        expect($seller->gigs)->toHaveCount(1);
        expect($seller->gigs->first()->id)->toBe($gig->id);
    });

    it('has many reviews', function () {
        ['seller' => $seller] = createSeller();
        $gig = createGig($seller);
        $buyer = createBuyer();
        $order = createOrder($buyer, $gig, Order::STATUS_COMPLETED);

        \App\Models\Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'gig_id' => $gig->id,
            'rating' => 5,
            'comment' => 'Great!',
        ]);

        expect($seller->reviews)->toHaveCount(1);
    });

    it('calculates average rating', function () {
        ['seller' => $seller] = createSeller();
        $gig = createGig($seller);

        // Create reviews with ratings 4 and 5
        foreach ([4, 5] as $rating) {
            $buyer = createBuyer();
            $order = createOrder($buyer, $gig, Order::STATUS_COMPLETED);
            \App\Models\Review::create([
                'order_id' => $order->id,
                'reviewer_id' => $buyer->id,
                'seller_id' => $seller->id,
                'gig_id' => $gig->id,
                'rating' => $rating,
            ]);
        }

        expect($seller->average_rating)->toBe(4.5);
    });

    it('casts skills to array', function () {
        ['seller' => $seller] = createSeller();

        expect($seller->skills)->toBeArray();
    });

    it('has status constants', function () {
        expect(Seller::STATUS_PENDING)->toBe('pending');
        expect(Seller::STATUS_APPROVED)->toBe('approved');
        expect(Seller::STATUS_REJECTED)->toBe('rejected');
        expect(Seller::STATUS_SUSPENDED)->toBe('suspended');
    });
});
