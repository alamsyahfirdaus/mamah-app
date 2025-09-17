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
}
