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
                'file_name'   => 'depresi.mp4',
                'description' => 'Depresi pascamelahirkan adalah kondisi mental serius yang dapat memengaruhi ibu setelah melahirkan. Modul ini menjelaskan gejala, penyebab, dan cara penanganannya.',
                'category_id' => 1, // Kesehatan Mental
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Informasi Perawatan Kehamilan Sehat',
                'media_type'  => 'video',
                'file_name'   => 'kehamilan.mp4',
                'description' => 'Modul ini berisi panduan kehamilan sehat, termasuk pola makan, olahraga ringan, dan tanda-tanda bahaya yang perlu diperhatikan.',
                'category_id' => 2, // Informasi Seputar Kehamilan
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Nutrisi Sehat untuk Ibu Nifas',
                'media_type'  => 'image',
                'file_name'   => 'nutrisi.jpg',
                'description' => 'Makanan bergizi seimbang sangat penting dalam masa pemulihan pasca persalinan. Artikel ini membahas makanan yang direkomendasikan.',
                'category_id' => 3, // Informasi Seputar Masa Nifas
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
