<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillageModel extends Model
{
    use HasFactory;

    protected $table = 'villages';
    protected $primaryKey = 'id';

    protected $guarded = [];

    // Relasi ke Kelurahan/Desa 
    public function district()
    {
        return $this->belongsTo(DistrictModel::class, 'district_id');
    }

    public static function getRegionList()
    {
        $villages = self::with('district.city.province')
            ->orderBy('name', 'asc')
            ->get();

        $regions = $villages->mapWithKeys(function ($village) {
            $villageName  = $village->name ?? '';
            $districtName = $village->district->name ?? '';
            $cityName     = $village->district->city->name ?? '';
            $provinceName = $village->district->city->province->name ?? '';

            $fullRegion = trim("{$villageName}, {$districtName}, {$cityName}, {$provinceName}", ', ');

            return [$village->id => $fullRegion];
        });

        return $regions->all();
    }
}
