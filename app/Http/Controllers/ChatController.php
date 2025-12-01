<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Ambil semua pesan untuk order tertentu
    public function index(Order $order)
    {
        // Cek akses (harus Pembeli atau Penjual di order ini)
        $this->authorizeChatAccess($order);

        return response()->json(
            $order->chatMessages()
                  ->with('user')
                  ->orderBy('created_at', 'asc')
                  ->get()
        );
    }

    // Kirim pesan baru
    public function store(Request $request, Order $order)
    {
        // Cek akses
        $this->authorizeChatAccess($order);

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat = ChatMessage::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Kembalikan data chat beserta info user pengirimnya
        return response()->json($chat->load('user'));
    }

    // Fungsi Helper: Cek apakah user berhak melihat/mengirim chat
    private function authorizeChatAccess(Order $order)
    {
        $userId = Auth::id();
        
        // PERBAIKAN DI SINI:
        // Menggunakan 'buyer_id' dan 'seller_id' sesuai tabel orders Anda
        if ($userId !== $order->buyer_id && $userId !== $order->seller_id) {
            abort(403, 'Anda tidak memiliki akses ke percakapan ini.');
        }
    }
}