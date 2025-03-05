<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        // Create a test student user
        User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_STUDENT,
            'email_verified_at' => now(),
        ]);
    }
} 