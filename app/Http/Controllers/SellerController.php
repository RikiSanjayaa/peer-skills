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
        // Redirect if already a seller
        if (Auth::user()->is_seller) {
            return redirect()->route('seller.dashboard');
        }

        $skills = Skill::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        return view('seller.register', compact('skills'));
    }

    public function store(Request $request)
    {
        // Redirect if already a seller
        if (Auth::user()->is_seller) {
            return redirect()->route('seller.dashboard');
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
            'is_active' => true,
        ]);

        // Update user to be a seller
        /** @var User $user */
        $user = Auth::user();
        $user->is_seller = true;
        $user->save();

        return redirect()->route('seller.dashboard')->with('success', 'Congratulations! you are now a seller.');
    }

    public function dashboard()
    {
        if (!Auth::user()->is_seller) {
            return redirect()->route('home');
        }

        $seller = Auth::user()->seller;
        return view('seller.dashboard', compact('seller'));
    }
}
