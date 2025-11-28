<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- 1. Tambahkan ini

class UserController extends Controller
{
    // 1. Tampilkan Daftar User
    public function index()
    {
        // 2. Gunakan Auth::id() biar garis merah hilang
        // Kita exclude diri sendiri biar gak salah pencet demote/delete akun sendiri
        $users = User::where('id', '!=', Auth::id())->latest()->get();
        
        return view('admin.users.index', compact('users'));
    }

    // 2. Angkat jadi Admin (Promote)
    public function promote(User $user)
    {
        $user->update(['role' => 'admin']);
        return redirect()->back()->with('success', 'User berhasil diangkat menjadi Admin!');
    }

    // 3. Turunkan jadi User biasa (Demote)
    public function demote(User $user)
    {
        $user->update(['role' => 'user']);
        return redirect()->back()->with('success', 'Akses Admin dicabut dari user ini.');
    }

    // 4. Hapus User
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus permanen.');
    }
}