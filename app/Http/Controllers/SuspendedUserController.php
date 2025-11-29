<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BanAppeal;

class SuspendedUserController extends Controller
{
    // 1. Tampilkan Halaman "Anda Kena Ban"
    public function show()
    {
        $user = Auth::user(); // Pakai variabel biar lebih rapi & dikenali VS Code

        // Pastikan user memang kena ban (kalau tidak, tendang ke home)
        if (!$user->suspended_until || $user->suspended_until->isPast()) {
            return redirect()->route('home');
        }

        return view('suspended');
    }

    // 2. Kirim Banding (Klarifikasi)
    public function storeAppeal(Request $request)
    {
        $request->validate(['message' => 'required|string|min:10']);

        BanAppeal::create([
            'user_id' => Auth::id(), // Pakai Auth::id() lebih singkat
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Permohonan banding Anda telah dikirim ke Admin.');
    }
}