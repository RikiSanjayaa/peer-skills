<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerRequestController extends Controller
{
    // 1. Tampilkan Daftar Request (Status Pending)
    public function index()
    {
        // Ambil seller yang statusnya 'pending', sertakan data user-nya
        $pendingSellers = Seller::where('status', 'pending')
                                ->with('user')
                                ->latest()
                                ->get();

        return view('admin.sellers.index', compact('pendingSellers'));
    }

    // 2. Logika Menyetujui (Approve)
    public function approve($id)
    {
        $seller = Seller::findOrFail($id);

        // A. Update Status Seller
        $seller->update([
            'status' => 'approved',
            'is_active' => true, // Aktifkan profil seller
        ]);

        // B. Ubah User jadi Seller (PENTING)
        $seller->user->update(['is_seller' => true]);

        return redirect()->back()->with('success', 'Seller berhasil disetujui! User sekarang bisa mengakses menu seller.');
    }

    // 3. Logika Menolak (Reject)
    public function reject($id)
    {
        $seller = Seller::findOrFail($id);

        // Hapus data pengajuan
        $seller->delete();

        return redirect()->back()->with('success', 'Pengajuan seller ditolak dan data dihapus.');
    }
}