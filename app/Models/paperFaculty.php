<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class paperFaculty extends Model
{
    use HasFactory,Notifiable;
    protected $fillable = [
        'paper_id',
        'faculty_id'
    ];
}
