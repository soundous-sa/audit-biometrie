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
            ['libelle' => 'السجن المحلي الناضور'],
            ['libelle' => 'السجن المحلي سلا '],
            ['libelle' => 'السجن المحلي بني ملالa'],
            ['libelle' => 'السجن المحلي الرشيدية '],
            ['libelle' => 'السجن المحلي بالدار البيضاء '],
            ['libelle' => 'السجن المحلي القنيطرة '],
            ['libelle' => 'السجن المحلي طنجة '],
            ['libelle' => 'السجن المحلي مراكش '],
            ['libelle' => 'السجن المحلي فاس '],
            ['libelle' => 'السجن المحلي وجدة '],
            ['libelle' => 'السجن المحلي تطوان '],
            ['libelle' => 'السجن المحلي آسفي '],
            ['libelle' => 'السجن المحلي خريبكة '],
            ['libelle' => 'السجن المحلي الجديدة '],
            ['libelle' => 'السجن المحلي برشيد '],
            ['libelle' => 'السجن المحلي القصر الكبير '],
            ['libelle' => 'السجن المحلي العرائش '],
            ['libelle' => 'السجن المحلي سيدي سليمان '],
            ['libelle' => 'السجن المحلي تاوريرت '],
            ['libelle' => 'السجن المحلي شيشاوة '],
            ['libelle' => 'السجن المحلي الصويرة '],
            ['libelle' => 'السجن المحلي زاكورة '],
            ['libelle' => 'السجن المحلي طاطا '],
            ['libelle' => 'السجن المحلي تنغير'],
            ['libelle' => 'السجن المحلي الدريوش'],
            ['libelle' => 'السجن المحلي بركان'],
            ['libelle' => 'السجن المحلي العيون'],
            ['libelle' => 'السجن المحلي السمارة'],
            ['libelle' => "المؤسسة السجنية عين قادوس"],
            ['libelle' => "المؤسسة السجنية واد لاو"],
            ['libelle' => "المؤسسة السجنية تيفلت"],
            ['libelle' => "المؤسسة السجنية سيدي بنور"],
            ['libelle' => "المؤسسة السجنية سيدي قاسم"],
            ['libelle' => "المؤسسة السجنية مولاي يعقوب"],
            ['libelle' => "المؤسسة السجنية الرحامنة"],
            ['libelle' => "المؤسسة السجنية الفقيه بن صالح"],
            ['libelle' => "المؤسسة السجنية الحاجب"],
            ['libelle' => "المؤسسة السجنية تارودانت"],
            ['libelle' => "المؤسسة السجنية طانطان"],
        ]);
    }
}
