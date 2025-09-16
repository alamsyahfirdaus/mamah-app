<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScreeningChoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $choices = [
            // Pertanyaan 1 & 2 (positif) → skor dibalik
            1 => [
                ['label' => 'Sering / Sama seperti biasanya', 'score' => 0],
                ['label' => 'Kadang-kadang', 'score' => 1],
                ['label' => 'Jarang', 'score' => 2],
                ['label' => 'Tidak sama sekali', 'score' => 3],
            ],
            2 => [
                ['label' => 'Sering / Sama seperti biasanya', 'score' => 0],
                ['label' => 'Kadang-kadang', 'score' => 1],
                ['label' => 'Jarang', 'score' => 2],
                ['label' => 'Hampir tidak pernah', 'score' => 3],
            ],
            // Pertanyaan 3–10 (negatif) → skor normal
            3 => [
                ['label' => 'Tidak pernah', 'score' => 0],
                ['label' => 'Jarang', 'score' => 1],
                ['label' => 'Sering', 'score' => 2],
                ['label' => 'Sangat sering', 'score' => 3],
            ],
            4 => [
                ['label' => 'Tidak pernah', 'score' => 0],
                ['label' => 'Jarang', 'score' => 1],
                ['label' => 'Sering', 'score' => 2],
                ['label' => 'Sangat sering', 'score' => 3],
            ],
            5 => [
                ['label' => 'Tidak pernah', 'score' => 0],
                ['label' => 'Jarang', 'score' => 1],
                ['label' => 'Sering', 'score' => 2],
                ['label' => 'Sangat sering', 'score' => 3],
            ],
            6 => [
                ['label' => 'Tidak pernah', 'score' => 0],
                ['label' => 'Jarang', 'score' => 1],
                ['label' => 'Sering', 'score' => 2],
                ['label' => 'Sangat sering', 'score' => 3],
            ],
            7 => [
                ['label' => 'Tidak pernah', 'score' => 0],
                ['label' => 'Jarang', 'score' => 1],
                ['label' => 'Sering', 'score' => 2],
                ['label' => 'Sangat sering', 'score' => 3],
            ],
            8 => [
                ['label' => 'Tidak pernah', 'score' => 0],
                ['label' => 'Jarang', 'score' => 1],
                ['label' => 'Sering', 'score' => 2],
                ['label' => 'Sangat sering', 'score' => 3],
            ],
            9 => [
                ['label' => 'Tidak pernah', 'score' => 0],
                ['label' => 'Jarang', 'score' => 1],
                ['label' => 'Sering', 'score' => 2],
                ['label' => 'Sangat sering', 'score' => 3],
            ],
            10 => [
                ['label' => 'Tidak pernah', 'score' => 0],
                ['label' => 'Hampir tidak pernah', 'score' => 1],
                ['label' => 'Kadang-kadang', 'score' => 2],
                ['label' => 'Cukup sering', 'score' => 3],
            ],
        ];

        foreach ($choices as $questionId => $options) {
            foreach ($options as $option) {
                DB::table('screening_choices')->insert([
                    'question_id' => $questionId,
                    'label' => $option['label'],
                    'score' => $option['score'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
