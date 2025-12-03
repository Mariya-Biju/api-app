<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PaperStudent extends Model
{
    use HasFactory,Notifiable;
    protected $fillable = [
        'student_id',
        'paper_id',
        'status'
    ];
}
