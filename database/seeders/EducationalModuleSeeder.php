<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationalModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('educational_modules')->insert([
            [
                'title' => 'Mengenal Depresi Pascamelahirkan',
                'content' => 'Depresi pascamelahirkan adalah kondisi mental serius yang dapat memengaruhi ibu setelah melahirkan. Modul ini menjelaskan gejala, penyebab, dan cara penanganannya.',
                'video_url' => 'https://www.youtube.com/watch?v=abc123',
                'image_url' => 'https://example.com/images/depresi.jpg',
                'category_id' => 1,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Perawatan Bayi Baru Lahir',
                'content' => 'Modul ini menjelaskan dasar-dasar merawat bayi seperti menyusui, mengganti popok, dan memahami tangisan bayi.',
                'video_url' => 'https://www.youtube.com/watch?v=xyz456',
                'image_url' => 'https://example.com/images/bayi.jpg',
                'category_id' => 2,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Nutrisi Sehat untuk Ibu Nifas',
                'content' => 'Makanan bergizi seimbang sangat penting dalam masa pemulihan pasca persalinan. Artikel ini membahas makanan yang direkomendasikan.',
                'video_url' => null,
                'image_url' => null,
                'category_id' => 3,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Mengelola Stres Pascamelahirkan',
                'content' => 'Pelajari strategi praktis untuk mengurangi stres dan kecemasan setelah melahirkan, termasuk relaksasi dan teknik mindfulness.',
                'video_url' => 'https://www.youtube.com/watch?v=stres001',
                'image_url' => null,
                'category_id' => 4,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Pentingnya Dukungan Sosial',
                'content' => 'Modul ini menekankan pentingnya dukungan dari pasangan, keluarga, dan komunitas dalam menjaga kesehatan mental ibu.',
                'video_url' => null,
                'image_url' => 'https://example.com/images/dukungan.jpg',
                'category_id' => 5,
                'is_visible' => false, // Tidak langsung tampil
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
