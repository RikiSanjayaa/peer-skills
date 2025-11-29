<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->is_seller) {
            return redirect()->route('seller.dashboard');
        }

        // Cek apakah ada pengajuan yang ditolak
        $rejectedSeller = Seller::where('user_id', $user->id)
            ->where('status', Seller::STATUS_REJECTED)
            ->latest()
            ->first();

        if ($rejectedSeller) {
            // Tampilkan halaman dengan info penolakan
            $skills = Skill::where('is_active', true)->orderBy('category')->orderBy('name')->get();
            return view('seller.register', [
                'skills' => $skills,
                'rejectedSeller' => $rejectedSeller,
                'isResubmit' => true,
            ]);
        }

        if ($user->seller && $user->seller->status === 'pending') {
            return redirect()->route('seller.status');
        }

        $skills = Skill::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        return view('seller.register', compact('skills'));
    }

    /**
     * Tampilkan status pengajuan seller
     */
    public function status()
    {
        $user = Auth::user();

        if ($user->is_seller) {
            return redirect()->route('seller.dashboard');
        }

        $seller = Seller::where('user_id', $user->id)->latest()->first();

        if (!$seller) {
            return redirect()->route('seller.register');
        }

        return view('seller.status', compact('seller'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Redirect if already a seller
        if ($user->is_seller) {
            return redirect()->route('seller.dashboard');
        }

        // Cek apakah ada pengajuan pending
        $pendingSeller = Seller::where('user_id', $user->id)
            ->where('status', Seller::STATUS_PENDING)
            ->first();

        if ($pendingSeller) {
            return redirect()->route('seller.status')->with('info', 'Anda sudah memiliki pengajuan yang sedang diproses.');
        }

        $validated = $request->validate([
            'business_name' => 'required|string|max:255|unique:sellers,business_name,' . ($request->resubmit_id ?? 'NULL'),
            'description' => 'required|string|max:1000',
            'major' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'skills' => 'required|array|min:1|max:10',
            'skills.*' => 'required|string|max:255',
        ]);

        // Jika ini adalah resubmit, hapus pengajuan lama yang ditolak
        if ($request->resubmit_id) {
            Seller::where('id', $request->resubmit_id)
                ->where('user_id', $user->id)
                ->where('status', Seller::STATUS_REJECTED)
                ->delete();
        }

        // Create new seller profile
        Seller::create([
            'user_id' => Auth::id(),
            'business_name' => $validated['business_name'],
            'description' => $validated['description'],
            'major' => $validated['major'],
            'university' => $validated['university'],
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'skills' => $validated['skills'],
            'is_active' => false,
            'status' => Seller::STATUS_PENDING,
        ]);

        return redirect()->route('seller.status')->with('success', 'Pengajuan berhasil dikirim! Mohon tunggu proses verifikasi.');
    }

    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->is_seller) {
            return redirect()->route('home');
        }

        $seller = $user->seller;
        $gigs = $seller->gigs()->with('category')->latest()->get();

        // Order statistics
        $pendingOrders = $user->sellerOrders()->whereIn('status', ['pending', 'quoted'])->count();
        $activeOrders = $user->sellerOrders()->whereIn('status', ['accepted', 'delivered', 'revision_requested'])->count();
        $completedOrders = $user->sellerOrders()->where('status', 'completed')->count();
        $totalEarnings = $user->sellerOrders()->where('status', 'completed')->sum('price');

        // Recent orders for quick view
        $recentOrders = $user->sellerOrders()
            ->with(['buyer', 'gig'])
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact(
            'seller',
            'gigs',
            'pendingOrders',
            'activeOrders',
            'completedOrders',
            'totalEarnings',
            'recentOrders'
        ));
    }
}
