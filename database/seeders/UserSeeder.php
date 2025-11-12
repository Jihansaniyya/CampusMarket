<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test buyer user
        User::create([
            'name' => 'Pembeli Test',
            'email' => 'buyer@test.com',
            'password' => Hash::make('password123'),
            'role' => 'buyer',
            'phone' => '081234567890',
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        // Create test seller user
        User::create([
            'name' => 'Penjual Test',
            'email' => 'seller@test.com',
            'password' => Hash::make('password123'),
            'role' => 'seller',
            'phone' => '089876543210',
            'store_name' => 'Toko Test',
            'description' => 'Toko penjual test untuk testing',
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'store_verified_at' => now(),
        ]);

        // Create 10 random buyers
        User::factory(10)->create([
            'role' => 'buyer',
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        // Create 5 random sellers
        User::factory(5)->create([
            'role' => 'seller',
            'store_name' => fake()->unique()->company(),
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'store_verified_at' => now(),
        ]);
    }
}
