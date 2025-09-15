<?php

namespace Database\Seeders;

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
                'title'       => 'Mengenal Depresi Pascamelahirkan',
                'media_type'  => 'video',
                'file_name'   => 'tes.mp4',
                'description' => 'Depresi pascamelahirkan adalah kondisi mental serius yang dapat memengaruhi ibu setelah melahirkan. Modul ini menjelaskan gejala, penyebab, dan cara penanganannya.',
                'category_id' => 1,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Perawatan Bayi Baru Lahir',
                'media_type'  => 'video',
                'file_name'   => 'tes.mp4',
                'description' => 'Modul ini menjelaskan dasar-dasar merawat bayi seperti menyusui, mengganti popok, dan memahami tangisan bayi.',
                'category_id' => 2,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Nutrisi Sehat untuk Ibu Nifas',
                'media_type'  => 'image',
                'file_name'   => 'nutrisi.jpg',
                'description' => 'Makanan bergizi seimbang sangat penting dalam masa pemulihan pasca persalinan. Artikel ini membahas makanan yang direkomendasikan.',
                'category_id' => 3,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Mengelola Stres Pascamelahirkan',
                'media_type'  => 'video',
                'file_name'   => 'tes.mp4',
                'description' => 'Pelajari strategi praktis untuk mengurangi stres dan kecemasan setelah melahirkan, termasuk relaksasi dan teknik mindfulness.',
                'category_id' => 4,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Pentingnya Dukungan Sosial',
                'media_type'  => 'image',
                'file_name'   => 'dukungan.jpg',
                'description' => 'Modul ini menekankan pentingnya dukungan dari pasangan, keluarga, dan komunitas dalam menjaga kesehatan mental ibu.',
                'category_id' => 5,
                'is_visible'  => false, // Tidak langsung tampil
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
