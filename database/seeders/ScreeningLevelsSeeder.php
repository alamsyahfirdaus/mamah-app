<?php

namespace Database\Seeders;

use App\Models\ScreeningQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScreeningLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID pertanyaan ke-10 sebagai pertanyaan khusus
        $specialQuestion = ScreeningQuestion::where('question_no', 10)->first();

        DB::table('screening_levels')->insert([
            // Level Umum (berdasarkan total skor)
            [
                'min_score'     => 0,
                'max_score'     => 7,
                'category'      => 'Tidak depresi',
                'recommendation' => 'Lanjutkan pemberian dukungan dan pemantauan berkala.',
                'question_id'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'min_score'     => 8,
                'max_score'     => 11,
                'category'      => 'Kemungkinan depresi ringan',
                'recommendation' => 'Beri dukungan emosional dan pertimbangkan skrining ulang dalam 2–4 minggu.',
                'question_id'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'min_score'     => 12,
                'max_score'     => 13,
                'category'      => 'Kemungkinan depresi sedang',
                'recommendation' => 'Konsultasikan dengan tenaga kesehatan dan pantau perkembangan.',
                'question_id'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'min_score'     => 14,
                'max_score'     => null,
                'category'      => 'Depresi sangat mungkin terjadi',
                'recommendation' => 'Segera rujuk ke tenaga kesehatan profesional atau psikiater.',
                'question_id'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            // Kondisi Khusus (Soal nomor 10)
            [
                'min_score'     => 1,
                'max_score'     => 3,
                'category'      => null,
                'recommendation' => 'Penting untuk segera melakukan asesmen lanjutan. Jika ada ide menyakiti diri, segera rujuk ke psikolog/psikiater.',
                'question_id'   => $specialQuestion?->id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
