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
            // === Kategori 1: Relaksasi ===
            [
                'title'       => 'Teknik Pernapasan untuk Relaksasi',
                'media_type'  => 'video',
                'file_name'   => 'relaksasi_pernapasan.mp4',
                'description' => 'Video ini berisi panduan teknik pernapasan sederhana untuk membantu ibu merasa lebih tenang dan mengurangi stres.',
                'category_id' => 1,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Musik Relaksasi untuk Ibu',
                'media_type'  => 'video',
                'file_name'   => 'musik_relaksasi.mp3',
                'description' => 'Kumpulan musik relaksasi yang dapat membantu ibu beristirahat lebih nyaman.',
                'category_id' => 1,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // === Kategori 2: Kesehatan Mental ===
            [
                'title'       => 'Mengenal Depresi Pascamelahirkan',
                'media_type'  => 'video',
                'file_name'   => 'depresi.mp4',
                'description' => 'Depresi pascamelahirkan adalah kondisi mental serius yang dapat memengaruhi ibu setelah melahirkan. Modul ini menjelaskan gejala, penyebab, dan cara penanganannya.',
                'category_id' => 2,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Tips Mengatasi Baby Blues',
                'media_type'  => 'video',
                'file_name'   => 'baby_blues.pdf',
                'description' => 'Dokumen ini berisi langkah-langkah praktis untuk membantu ibu mengatasi baby blues setelah melahirkan.',
                'category_id' => 2,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // === Kategori 3: Seputar Kehamilan ===
            [
                'title'       => 'Informasi Perawatan Kehamilan Sehat',
                'media_type'  => 'video',
                'file_name'   => 'kehamilan.mp4',
                'description' => 'Panduan kehamilan sehat, termasuk pola makan, olahraga ringan, dan tanda-tanda bahaya yang perlu diperhatikan.',
                'category_id' => 3,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Checklist Persiapan Persalinan',
                'media_type'  => 'document',
                'file_name'   => 'checklist_persiapan.pdf',
                'description' => 'Daftar barang dan persiapan penting yang harus dilakukan menjelang persalinan.',
                'category_id' => 3,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // === Kategori 4: Seputar Masa Nifas ===
            [
                'title'       => 'Nutrisi Sehat untuk Ibu Nifas',
                'media_type'  => 'image',
                'file_name'   => 'nutrisi.jpg',
                'description' => 'Makanan bergizi seimbang sangat penting dalam masa pemulihan pasca persalinan. Artikel ini membahas makanan yang direkomendasikan.',
                'category_id' => 4,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Perawatan Diri Selama Masa Nifas',
                'media_type'  => 'video',
                'file_name'   => 'perawatan_nifas.pdf',
                'description' => 'Modul ini berisi tips perawatan diri, kebersihan, dan pemantauan kesehatan selama masa nifas.',
                'category_id' => 4,
                'is_visible'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
