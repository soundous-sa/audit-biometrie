<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\EtablissementsSeeder; 
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Exemple : création d'un utilisateur test
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        // Appeler  le seeder d'Etablissements
        $this->call(EtablissementsSeeder::class);
    }
}
