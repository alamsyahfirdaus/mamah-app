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
                'name' => 'Informasi Seputar Kehamilan',
                'description' => 'Materi dan informasi terkait kesehatan serta persiapan selama masa kehamilan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Informasi Seputar Masa Nifas',
                'description' => 'Edukasi dan panduan kesehatan untuk ibu di masa nifas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
