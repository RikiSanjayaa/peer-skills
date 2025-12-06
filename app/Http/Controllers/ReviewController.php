<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new review for a completed order
     */
    public function store(Request $request, Order $order)
    {
        // Validate that the user is the buyer and the order is completed
        if ($order->buyer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk memberikan ulasan pada pesanan ini.');
        }

        if ($order->status !== Order::STATUS_COMPLETED) {
            return back()->with('error', 'Anda hanya bisa memberikan ulasan untuk pesanan yang sudah selesai.');
        }

        // Check if review already exists
        if ($order->review) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'order_id' => $order->id,
            'reviewer_id' => Auth::id(),
            'seller_id' => $order->gig->seller_id,
            'gig_id' => $order->gig_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda telah berhasil dikirim.');
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, Order $order)
    {
        // Validate that the user is the buyer
        if ($order->buyer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah ulasan ini.');
        }

        // Check if review exists
        if (!$order->review) {
            return back()->with('error', 'Ulasan tidak ditemukan.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $order->review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Ulasan Anda telah berhasil diperbarui.');
    }
}
