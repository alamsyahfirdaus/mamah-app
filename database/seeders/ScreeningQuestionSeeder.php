<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScreeningQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('screening_questions')->insert([
            [
                'question_no' => 1,
                'question_text' => 'Saya dapat menertawakan dan melihat sisi lucu dari sesuatu.',
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question_no' => 2,
                'question_text' => 'Saya menantikan sesuatu dengan senang hati.',
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question_no' => 3,
                'question_text' => 'Saya menyalahkan diri sendiri tanpa alasan ketika ada yang salah.',
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question_no' => 4,
                'question_text' => 'Saya merasa cemas atau khawatir tanpa alasan yang jelas.',
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question_no' => 5,
                'question_text' => 'Saya merasa takut atau panik tanpa alasan yang jelas.',
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question_no' => 6,
                'question_text' => 'Hal-hal yang sebelumnya saya anggap mudah kini menjadi sulit.',
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question_no' => 7,
                'question_text' => 'Saya merasa tidak mampu menghadapi hal-hal yang terjadi.',
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question_no' => 8,
                'question_text' => 'Saya merasa sedih atau tertekan.',
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question_no' => 9,
                'question_text' => 'Saya merasa sangat tidak bahagia hingga saya menangis.',
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question_no' => 10,
                'question_text' => 'Pikiran untuk menyakiti diri sendiri pernah terlintas dalam pikiran saya.',
                'is_special' => true, // Pertanyaan khusus (berisiko tinggi)
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
