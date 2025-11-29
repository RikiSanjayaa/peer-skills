<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // 1. Tampilkan Form Review
    public function create(Order $order)
    {
        // Cek Keamanan:
        // 1. Apakah user ini benar pembelinya?
        if (Auth::id() !== $order->buyer_id) {
            abort(403, 'Anda tidak berhak mereview pesanan ini.');
        }
        
        // 2. Apakah status order sudah selesai?
        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'Pesanan harus selesai dulu baru bisa direview.');
        }

        // 3. Apakah sudah pernah direview?
        if ($order->review) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        return view('reviews.create', compact('order'));
    }

    // 2. Simpan Review
    public function store(Request $request, Order $order)
    {
        // Validasi
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Simpan ke Database
        Review::create([
            'order_id' => $order->id,
            'gig_id' => $order->gig_id,
            'reviewer_id' => Auth::id(),
            'seller_id' => $order->seller_id, // Kita ambil ID seller dari data order
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('orders.show', $order->id)->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}