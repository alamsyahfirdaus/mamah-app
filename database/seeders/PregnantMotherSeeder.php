<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PregnantMotherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pregnant_mothers')->insert([
            [
                'user_id' => 3, // pastikan user_id ini adalah user dengan role ibu hamil
                'mother_age' => 30,
                'pregnancy_number' => 2,
                'live_children_count' => 1,
                'miscarriage_history' => 0,
                'mother_disease_history' => 'Tidak ada riwayat penyakit kronis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7, // user lain dengan role ibu hamil
                'mother_age' => 28,
                'pregnancy_number' => 1,
                'live_children_count' => 0,
                'miscarriage_history' => 0,
                'mother_disease_history' => 'Riwayat anemia ringan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
