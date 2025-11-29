<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BanAppeal;
use Illuminate\Http\Request;

class BanAppealController extends Controller
{
    // 1. Lihat Daftar Banding
    public function index()
    {
        $appeals = BanAppeal::with('user')->where('status', 'pending')->latest()->get();
        return view('admin.appeals.index', compact('appeals'));
    }

    // 2. Terima Banding (Unban User)
    public function approve($id)
    {
        $appeal = BanAppeal::findOrFail($id);
        
        // A. Bebaskan User (Hapus tanggal suspend)
        $appeal->user->update(['suspended_until' => null]);

        // B. Update Status Banding
        $appeal->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Banding diterima. User telah dibebaskan!');
    }

    // 3. Tolak Banding (Tetap Dihukum)
    public function reject($id)
    {
        $appeal = BanAppeal::findOrFail($id);
        $appeal->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Banding ditolak. User tetap disuspend.');
    }
}