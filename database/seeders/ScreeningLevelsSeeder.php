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
                'max_score'     => 9,
                'category'      => 'Normal',
                'recommendation' => 'Tidak ada tanda depresi yang berarti. Tetap jaga hati agar tenang dengan banyak berdoa, dzikir, dan bersyukur atas amanah menjadi seorang ibu. Menjaga emosi juga bagian dari ibadah.',
                'question_id'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'min_score'     => 10,
                'max_score'     => 12,
                'category'      => 'Risiko Ringan',
                'recommendation' => 'Ada tanda sedikit sedih atau cemas, hal ini masih wajar. Perbanyak komunikasi dengan suami/keluarga, lakukan istighfar, shalat sunnah, dan berdoa agar Allah menenangkan jiwa.',
                'question_id'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'min_score'     => 13,
                'max_score'     => 14,
                'category'      => 'Risiko Sedang',
                'recommendation' => 'Gejala depresi mulai terlihat. Segera berbicara dengan bidan, konselor, atau tenaga kesehatan. Islam mengajarkan ta’awun (saling tolong-menolong), jadi jangan memendam sendiri. Mintalah dukungan keluarga dan sahabat.',
                'question_id'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'min_score'     => 15,
                'max_score'     => null, // null berarti ≥15
                'category'      => 'Risiko Tinggi',
                'recommendation' => 'Ada tanda kuat depresi setelah melahirkan. Ibu perlu segera dirujuk ke psikolog atau psikiater. Ingat sabda Rasulullah ﷺ: “Sesungguhnya badanmu memiliki hak atasmu.” (HR. Bukhari). Menjaga kesehatan jiwa adalah bagian dari ibadah dan rasa syukur kepada Allah.',
                'question_id'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            // Kondisi Khusus (Soal nomor 10)
            [
                'min_score'     => 1,
                'max_score'     => 3, // Jika jawaban "Ya" pada pertanyaan 10
                'category'      => 'Risiko Khusus',
                'recommendation' => 'Ibu perlu segera ditolong dengan berkonsultasi ke tenaga kesehatan jiwa (psikolog atau psikiater). Jangan dipendam sendiri, ceritakan pada keluarga atau orang terdekat. Ingat, dalam Islam menjaga jiwa adalah amanah Allah, dan setiap ujian pasti ada jalan keluarnya.',
                'question_id'   => $specialQuestion?->id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
