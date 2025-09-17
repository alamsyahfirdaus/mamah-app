<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Insert Province Jawa Barat (jika belum ada)
        $provinceId = DB::table('provinces')->updateOrInsert(
            ['name' => 'Jawa Barat'],
            ['created_at' => now(), 'updated_at' => now()]
        );
        $provinceId = DB::table('provinces')->where('name', 'Jawa Barat')->value('id');

        // Insert Cities di Jawa Barat
        $cities = [
            'Kota Tasikmalaya',
        ];

        $cityIds = [];
        foreach ($cities as $city) {
            DB::table('cities')->updateOrInsert(
                ['name' => $city, 'province_id' => $provinceId],
                ['created_at' => now(), 'updated_at' => now()]
            );
            $cityIds[$city] = DB::table('cities')->where('name', $city)->value('id');
        }

        // Kecamatan Kota Tasikmalaya
        $districtsKota = [
            'Cibeureum',
            'Cihideung',
            'Cipedes',
            'Indihiang',
            'Kawalu',
            'Mangkubumi',
            'Purbaratu',
            'Tamansari',
            'Tawang',
            'Bungursari',
        ];

        $districtIds = [];
        foreach ($districtsKota as $district) {
            DB::table('districts')->updateOrInsert(
                ['name' => $district, 'city_id' => $cityIds['Kota Tasikmalaya']],
                ['created_at' => now(), 'updated_at' => now()]
            );
            $districtIds[$district] = DB::table('districts')
                ->where('name', $district)
                ->where('city_id', $cityIds['Kota Tasikmalaya'])
                ->value('id');
        }

        // Kelurahan di Kecamatan Tamansari
        $villagesTamansari = [
            'Mugarsari',
            'Mulyasari',
            'Setiamulya',
            'Setiawargi',
            'Sukahurip',
            'Sumelap',
            'Tamanjaya',
            'Tamansari',
        ];

        foreach ($villagesTamansari as $village) {
            DB::table('villages')->updateOrInsert(
                ['name' => $village, 'district_id' => $districtIds['Tamansari']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // Kelurahan di Kecamatan Kawalu
        $villagesKawalu = [
            'Cibeuti',
            'Cilamajang',
            'Gununggede',
            'Gunungtandala',
            'Karanganyar',
            'Karsamenak',
            'Leuwiliang',
            'Talagasari',
            'Tanjung',
            'Urug',
        ];

        foreach ($villagesKawalu as $village) {
            DB::table('villages')->updateOrInsert(
                ['name' => $village, 'district_id' => $districtIds['Kawalu']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
