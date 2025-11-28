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

        if ($user->seller && $user->seller->status === 'pending') {
            return redirect('/')->with('info', 'Aplikasi Seller Anda sedang ditinjau oleh Admin.');
        }

        $skills = Skill::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        return view('seller.register', compact('skills'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        // Redirect if already a seller
        if (Auth::user()->is_seller) {
            return redirect()->route('seller.dashboard');
        }
        
        if ($user->seller) {
            return redirect('/')->with('error', 'Anda sudah memiliki pendaftaran Seller yang sedang diproses atau aktif.');
        }

        $validated = $request->validate([
            'business_name' => 'required|string|max:255|unique:sellers,business_name',
            'description' => 'required|string|max:1000',
            'major' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'skills' => 'required|array|min:1|max:10',
            'skills.*' => 'required|string|max:255',
        ]);

        // Create seller profile
        Seller::create([
            'user_id' => Auth::id(),
            'business_name' => $validated['business_name'],
            'description' => $validated['description'],
            'major' => $validated['major'],
            'university' => $validated['university'],
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'skills' => $validated['skills'],
            // riki, saya tambahin status pending buat verifikasi seller oleh admin
            'is_active' => false,
            'status' => 'pending',
        ]);

        // Update user to be a seller
        /** @var User $user */
        $user = Auth::user();
        // $user->is_seller = true;
        // $user->save();

return redirect('/')->with('success', 'Permintaan menjadi Seller berhasil dikirim! Mohon tunggu persetujuan Admin.');    }

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
