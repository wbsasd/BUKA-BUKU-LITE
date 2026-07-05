<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'user@user.com';

        if (User::where('email', $email)->exists()) {
            return;
        }

        User::create([
            'name' => 'Test User',
            'email' => $email,
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
            'role' => 'pengguna',

        ]);
    }
}

