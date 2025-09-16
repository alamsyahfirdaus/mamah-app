<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interpretation extends Model
{
    use HasFactory;

    protected $table = 'screening_levels';
    protected $primaryKey = 'id';

    protected $guarded = [];
}
