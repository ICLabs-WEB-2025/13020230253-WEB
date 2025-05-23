<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_approved' => true,
        ]);

        User::factory()->create([
            'name' => 'Agent User',
            'email' => 'agent@example.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'is_approved' => true,
        ]);

        User::factory()->create([
            'name' => 'Buyer User',
            'email' => 'buyer@example.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'is_approved' => true,
        ]);
    }
}