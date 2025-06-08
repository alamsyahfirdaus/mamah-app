<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('support_groups')->insert([
            [
                'name' => 'Ibu Hebat Tasikmalaya',
                'description' => 'Grup dukungan bagi ibu postpartum di wilayah Tasikmalaya.',
                'created_by' => 1, // Alamsyah Firdaus (role: kia)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Komunitas Ibu Bahagia',
                'description' => 'Tempat berbagi cerita dan dukungan emosional antar ibu.',
                'created_by' => 2, // Siti Nurhaliza
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bidan Peduli Mental',
                'description' => 'Diskusi bidan dan ibu tentang kesehatan mental pasca melahirkan.',
                'created_by' => 4, // Ayu Lestari
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sahabat KIA Sukabumi',
                'description' => 'Komunitas yang dibentuk oleh kader KIA di wilayah Sukabumi.',
                'created_by' => 6, // Dewi Kartika
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Forum Ibu & Bidan Jawa Barat',
                'description' => 'Forum gabungan untuk berbagi pengalaman dan tips kesehatan.',
                'created_by' => 5, // Sari Wijaya
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
