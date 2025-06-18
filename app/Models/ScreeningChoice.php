<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreeningChoice extends Model
{
    use HasFactory;

    protected $table = 'screening_choices';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(ScreeningQuestion::class, 'question_id');
    }
}
