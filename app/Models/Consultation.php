<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $table = 'consultations';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function ibu()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bidan()
    {
        return $this->belongsTo(User::class, 'bidan_id');
    }

    public function reply()
    {
        return $this->hasMany(ConsultationReply::class, 'consultation_id');
    }
}
