<?php

use App\Models\Gig;
use App\Models\Category;
use App\Models\Review;
use App\Models\Order;

describe('Gig Model', function () {

    it('belongs to seller', function () {
        $gig = createGig();

        expect($gig->seller)->toBeInstanceOf(\App\Models\Seller::class);
    });

    it('belongs to category', function () {
        $gig = createGig();

        expect($gig->category)->toBeInstanceOf(Category::class);
    });

    it('has many reviews', function () {
        $gig = createGig();
        $buyer = createBuyer();
        $order = createOrder($buyer, $gig, Order::STATUS_COMPLETED);

        Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $buyer->id,
            'seller_id' => $gig->seller_id,
            'gig_id' => $gig->id,
            'rating' => 5,
        ]);

        expect($gig->reviews)->toHaveCount(1);
    });

    it('calculates average rating', function () {
        $gig = createGig();

        foreach ([3, 4, 5] as $rating) {
            $buyer = createBuyer();
            $order = createOrder($buyer, $gig, Order::STATUS_COMPLETED);
            Review::create([
                'order_id' => $order->id,
                'reviewer_id' => $buyer->id,
                'seller_id' => $gig->seller_id,
                'gig_id' => $gig->id,
                'rating' => $rating,
            ]);
        }

        expect($gig->average_rating)->toBe(4.0);
    });

    it('returns null average when no reviews', function () {
        $gig = createGig();

        expect($gig->average_rating)->toBeNull();
    });

    it('counts reviews correctly', function () {
        $gig = createGig();

        for ($i = 0; $i < 3; $i++) {
            $buyer = createBuyer();
            $order = createOrder($buyer, $gig, Order::STATUS_COMPLETED);
            Review::create([
                'order_id' => $order->id,
                'reviewer_id' => $buyer->id,
                'seller_id' => $gig->seller_id,
                'gig_id' => $gig->id,
                'rating' => 5,
            ]);
        }

        expect($gig->review_count)->toBe(3);
    });

    it('casts images to array', function () {
        $gig = createGig();
        $gig->update(['images' => ['image1.jpg', 'image2.jpg']]);

        expect($gig->images)->toBeArray();
        expect($gig->images)->toHaveCount(2);
    });

    it('casts allows_tutoring to boolean', function () {
        $gig = createGig();

        expect($gig->allows_tutoring)->toBeBool();
    });
});

describe('Category Model', function () {

    it('has many gigs', function () {
        $category = createCategory();
        ['seller' => $seller] = createSeller();
        createGig($seller, $category);
        createGig($seller, $category);

        expect($category->gigs)->toHaveCount(2);
    });
});
