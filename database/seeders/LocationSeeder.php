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
            'Kab. Tasikmalaya',
            'Kota Bandung',
            'Kota Bekasi',
            'Kota Bogor',
            'Kota Cimahi',
            'Kota Cirebon',
            'Kota Depok',
            'Kota Sukabumi',
            'Kab. Bandung',
            'Kab. Bandung Barat',
            'Kab. Bekasi',
            'Kab. Bogor',
            'Kab. Ciamis',
            'Kab. Cianjur',
            'Kab. Cirebon',
            'Kab. Garut',
            'Kab. Indramayu',
            'Kab. Karawang',
            'Kab. Kuningan',
            'Kab. Majalengka',
            'Kab. Pangandaran',
            'Kab. Purwakarta',
            'Kab. Subang',
            'Kab. Sukabumi',
            'Kab. Sumedang',
        ];

        $cityIds = [];
        foreach ($cities as $city) {
            $cityIds[$city] = DB::table('cities')->updateOrInsert(
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

        foreach ($districtsKota as $district) {
            DB::table('districts')->updateOrInsert(
                ['name' => $district, 'city_id' => $cityIds['Kota Tasikmalaya']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // Kecamatan Kabupaten Tasikmalaya
        $districtsKab = [
            'Bantarkalong',
            'Bojongasih',
            'Bojonggambir',
            'Ciawi',
            'Cibalong',
            'Cigalontang',
            'Cikalong',
            'Cikatomas',
            'Cineam',
            'Cipatujah',
            'Cisayong',
            'Culamega',
            'Gunung Tanjung',
            'Jamanis',
            'Jatiwaras',
            'Kadipaten',
            'Karang Jaya',
            'Karangnunggal',
            'Leuwisari',
            'Mangunreja',
            'Manonjaya',
            'Padakembang',
            'Pagerageung',
            'Pancatengah',
            'Parungponteng',
            'Puspahiang',
            'Rajapolah',
            'Salawu',
            'Salopa',
            'Sariwangi',
            'Singaparna',
            'Sodonghilir',
            'Sukahening',
            'Sukaraja',
            'Sukarame',
            'Sukaratu',
            'Sukaresik',
            'Tanjungjaya',
            'Taraju',
        ];

        foreach ($districtsKab as $district) {
            DB::table('districts')->updateOrInsert(
                ['name' => $district, 'city_id' => $cityIds['Kab. Tasikmalaya']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
