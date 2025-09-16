<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\EtablissementsSeeder; 
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure base test user exists (idempotent)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'user'
            ]
        );

        // Run other seeders
        $this->call([
            AdminUserSeeder::class,
            UserSeeder::class,
            EtablissementsSeeder::class,
        ]);
    }
}
