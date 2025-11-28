<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Gig;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // Data statistik untuk dashboard
        $stats = [
            'total_users' => User::count(),
            'total_gigs' => Gig::count(),
            'total_orders' => Order::count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        // 5 User yang baru daftar
        $latestUsers = User::latest()->take(5)->get();

        // Mengarah ke file resources/views/admin/dashboard.blade.php
        return view('admin.dashboard', compact('stats', 'latestUsers'));
    }
}