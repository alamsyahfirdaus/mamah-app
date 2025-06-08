<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsultationReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('consultation_replies')->insert([
            [
                'consultation_id' => 1,
                'sender_id' => 2, // Ibu Siti Nurhaliza
                'message' => 'Saya merasa sangat cemas dan tidak bisa tidur.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'consultation_id' => 1,
                'sender_id' => 4, // Bidan Ayu Lestari
                'message' => 'Terima kasih sudah berbagi. Apakah Ibu sudah mencoba teknik relaksasi seperti pernapasan dalam?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'consultation_id' => 2,
                'sender_id' => 3, // Rina Amelia
                'message' => 'Saya sering merasa marah tanpa alasan.',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'consultation_id' => 2,
                'sender_id' => 5, // Bidan Sari Wijaya
                'message' => 'Hal tersebut bisa terjadi karena perubahan hormon. Kami sarankan Ibu lebih banyak istirahat.',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'consultation_id' => 3,
                'sender_id' => 7, // Dinda Rosdiana
                'message' => 'Saya merasa mudah menangis, bahkan saat tidak ada masalah.',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);
    }
}
