<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationCategory extends Model
{
    use HasFactory;

    protected $table = 'module_categories';
    protected $primaryKey = 'id';

    protected $guarded = [];
}
