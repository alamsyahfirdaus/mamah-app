<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreeningQuestion extends Model
{
    use HasFactory;

    protected $table = 'screening_questions';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function choices()
    {
        return $this->hasMany(ScreeningChoice::class, 'question_id');
    }
}
