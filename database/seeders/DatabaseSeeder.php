<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create/ensure default test user for manual login
        $this->call(\Database\Seeders\TestUserSeeder::class);

        // Create default admin if none exists
        $this->call(\Database\Seeders\AdminSeeder::class);

    }
}
