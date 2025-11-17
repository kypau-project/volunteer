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
        // Admin (Superuser)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Coordinator
        User::create([
            'name' => 'Coordinator User',
            'email' => 'coordinator@example.com',
            'password' => Hash::make('password'),
            'role' => 'coordinator',
            'email_verified_at' => now(),
        ]);

        // Volunteer
        User::create([
            'name' => 'Volunteer User',
            'email' => 'volunteer@example.com',
            'password' => Hash::make('password'),
            'role' => 'volunteer',
            'email_verified_at' => now(),
        ]);
    }
}

