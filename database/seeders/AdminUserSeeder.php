<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@coolhaxposts.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'currency' => 'INR',
            'balance_inr' => 0,
            'balance_usd' => 0,
            'email_verified_at' => now(),
        ]);

        // Create Regular User
        User::create([
            'name' => 'John Doe',
            'email' => 'john@coolhaxposts.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'currency' => 'INR',
            'balance_inr' => 0,
            'balance_usd' => 0,
            'email_verified_at' => now(),
        ]);

        // Create Demo User
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@coolhaxposts.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'currency' => 'USD',
            'balance_inr' => 0,
            'balance_usd' => 0,
            'email_verified_at' => now(),
        ]);
    }
}
