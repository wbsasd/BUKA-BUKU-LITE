<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (User::where('role', 'admin')->exists()) {
            return;
        }

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@bukabukulite.id',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
        ]);
    }
}
