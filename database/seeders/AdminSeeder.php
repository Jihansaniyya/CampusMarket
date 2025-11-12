<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Account
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@campusmarket.com',
            'phone' => '081234567890',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Sample Sellers
        User::create([
            'name' => 'John Seller',
            'email' => 'seller1@example.com',
            'phone' => '081234567891',
            'password' => Hash::make('Password123'),
            'role' => 'seller',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Jane Merchant',
            'email' => 'seller2@example.com',
            'phone' => '081234567892',
            'password' => Hash::make('Password123'),
            'role' => 'seller',
            'is_active' => true,
        ]);

        // Create Sample Buyers
        User::create([
            'name' => 'Alice Buyer',
            'email' => 'buyer1@example.com',
            'phone' => '081234567893',
            'password' => Hash::make('Password123'),
            'role' => 'buyer',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Bob Customer',
            'email' => 'buyer2@example.com',
            'phone' => '081234567894',
            'password' => Hash::make('Password123'),
            'role' => 'buyer',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Charlie Student',
            'email' => 'buyer3@example.com',
            'phone' => '081234567895',
            'password' => Hash::make('Password123'),
            'role' => 'buyer',
            'is_active' => true,
        ]);
    }
}
