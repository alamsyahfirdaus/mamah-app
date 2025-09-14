<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictModel extends Model
{
    use HasFactory;

    protected $table = 'districts';
    protected $primaryKey = 'id';

    protected $guarded = [];

    // Relasi ke City
    public function city()
    {
        return $this->belongsTo(CityModel::class, 'city_id');
    }

    public static function getRegionList()
    {
        $districts = self::with('city.province')
            ->orderBy('name', 'asc')
            ->get();

        $regions = $districts->mapWithKeys(function ($district) {
            $districtName = $district->name ?? '';
            $cityName     = $district->city->name ?? '';
            $provinceName = $district->city->province->name ?? '';

            $fullRegion = trim("{$districtName}, {$cityName}, {$provinceName}", ', ');

            return [$district->id => $fullRegion];
        });

        return $regions->all();
    }
}
