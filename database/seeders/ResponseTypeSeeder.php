<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResponseType;

class ResponseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['ننوه', 'بدل جهد', 'لا يتقيدون'];

        foreach ($types as $type) {
            ResponseType::create(['name' => $type]);
        }
    }
    }

