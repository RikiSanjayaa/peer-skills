<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create buyer test account
        User::create([
            'name' => 'Buyer User',
            'email' => 'buyer@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_seller' => false,
            'role' => 'user',
        ]);

        // Create seller test account
        $seller = User::create([
            'name' => 'Seller User',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_seller' => true,
            'role' => 'user',
        ]);

        // Create seller profile for seller user
        Seller::create([
            'user_id' => $seller->id,
            'business_name' => 'SellerPro',
            'description' => 'Professional freelancer specializing in web development and design.',
            'major' => 'Computer Science',
            'university' => 'MIT',
            'portfolio_url' => 'https://sellerpro.example.com',
            'skills' => ['Web Development', 'Laravel', 'UI/UX Design', 'JavaScript', 'React'],
            'is_active' => true,
        ]);
    }
}
