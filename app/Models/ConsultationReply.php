<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationReply extends Model
{
    use HasFactory;

    protected $table = 'consultation_replies';
    protected $primaryKey = 'id';

    protected $guarded = [];
}
