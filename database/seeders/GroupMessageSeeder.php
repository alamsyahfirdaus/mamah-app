<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('group_messages')->insert([
            [
                'group_id' => 1,
                'user_id' => 2,
                'message' => 'Halo semuanya, senang bisa bergabung di grup ini!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_id' => 1,
                'user_id' => 4,
                'message' => 'Selamat datang! Jangan ragu bertanya ya, Bu Siti.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_id' => 2,
                'user_id' => 3,
                'message' => 'Apakah ada yang pernah merasa cemas berlebihan setelah melahirkan?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_id' => 2,
                'user_id' => 7,
                'message' => 'Saya pernah Bu, tapi alhamdulillah bisa membaik setelah konsultasi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_id' => 3,
                'user_id' => 4,
                'message' => 'Jangan lupa lakukan EPDS screening ya ibu-ibu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_id' => 4,
                'user_id' => 6,
                'message' => 'Kami dari tim KIA siap bantu jika ada keluhan mental health.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_id' => 5,
                'user_id' => 5,
                'message' => 'Terima kasih telah bergabung di grup Forum Ibu & Bidan Jabar.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
