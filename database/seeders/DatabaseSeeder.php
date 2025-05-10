<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Optional: create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
        ]);

        $this->call(AdminSeeder::class);
    }
}
