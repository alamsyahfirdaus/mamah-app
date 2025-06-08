<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScreeningResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('screening_results')->insert([
            [
                'user_id' => 2, // Siti Nurhaliza
                'score' => 8,
                'category' => 'rendah',
                'recommendation' => 'Tidak perlu intervensi, cukup pemantauan rutin.',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'user_id' => 3, // Rina Amelia
                'score' => 14,
                'category' => 'sedang',
                'recommendation' => 'Konsultasi lanjutan dengan tenaga kesehatan disarankan.',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id' => 7, // Dinda Rosdiana
                'score' => 22,
                'category' => 'tinggi',
                'recommendation' => 'Segera hubungi tenaga profesional atau psikolog.',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => 8, // Diah Anggraini
                'score' => 11,
                'category' => 'sedang',
                'recommendation' => 'Berikan perhatian khusus dan edukasi lanjutan.',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'user_id' => 10, // Rosi Annisa
                'score' => 4,
                'category' => 'rendah',
                'recommendation' => 'Tidak ada indikasi depresi, edukasi tetap diberikan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
