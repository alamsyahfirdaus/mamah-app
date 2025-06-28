<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('consultations')->insert([
            [
                'user_id' => 2, // Ibu Siti Nurhaliza
                'bidan_id' => 4, // Bidan Ayu Lestari
                'topic' => 'Sulit tidur setelah melahirkan',
                // 'status' => 'terbuka',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3, // Rina Amelia
                'bidan_id' => 5, // Bidan Sari Wijaya
                'topic' => 'Perubahan suasana hati yang drastis',
                // 'status' => 'ditutup',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id' => 7, // Dinda Rosdiana
                'bidan_id' => 4, // Bidan Ayu Lestari
                'topic' => 'Sering menangis tanpa sebab',
                // 'status' => 'terbuka',
                'created_at' => now()->subDays(2),
                'updated_at' => now(),
            ],
        ]);
    }
}
