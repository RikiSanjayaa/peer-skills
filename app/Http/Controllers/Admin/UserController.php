<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user (exclude admin dan diri sendiri)
     */
    public function index()
    {
        // Exclude diri sendiri dan admin lain (admin hanya bisa dibuat via seeder)
        $users = User::where('id', '!=', Auth::id())
            ->where('role', '!=', 'admin')
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Hapus user biasa (non-admin)
     */
    public function destroy(User $user)
    {
        // Pastikan tidak bisa hapus admin
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun admin.');
        }

        // Hapus seller profile jika ada
        if ($user->seller) {
            $user->seller->gigs()->delete();
            $user->seller->delete();
        }

        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
    public function unban(User $user)
    {
        $user->update(['suspended_until' => null]); // Hapus tanggal hukuman

        // Opsional: Tandai bandingnya (kalau ada) jadi approved
        \App\Models\BanAppeal::where('user_id', $user->id)->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'User berhasil dibebaskan (Unbanned)!');
    }
}
