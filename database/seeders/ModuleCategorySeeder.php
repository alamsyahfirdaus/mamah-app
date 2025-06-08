<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('module_categories')->insert([
            [
                'name' => 'Kesehatan Mental',
                'description' => 'Edukasi mengenai kesehatan mental ibu setelah melahirkan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pola Asuh Bayi',
                'description' => 'Tips dan panduan merawat bayi baru lahir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nutrisi Ibu',
                'description' => 'Nutrisi penting selama masa nifas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manajemen Stres',
                'description' => 'Cara mengelola stres dan emosi pascamelahirkan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hubungan Sosial',
                'description' => 'Dukungan sosial dan komunikasi dalam keluarga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
