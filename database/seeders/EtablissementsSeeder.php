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
            ['libele' => 'Prison Centrale Rabat'],
            ['libele' => 'Prison Locale Casablanca'],
            ['libele' => 'Prison Locale Kénitra'],
            ['libele' => 'Prison Oujda'],
            ['libele' => 'Prison Marrakech'],
        ]);
    }
}
