<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EtablissementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('etablissements')->insert([
            ['libelle' => 'Prison Centrale Rabat'],
            ['libelle' => 'Prison Locale Casablanca'],
            ['libelle' => 'Prison Locale Kénitra'],
            ['libelle' => 'Prison Oujda'],
            ['libelle' => 'Prison Marrakech'],
        ]);
    }
}
