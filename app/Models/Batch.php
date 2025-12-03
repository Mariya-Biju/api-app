<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Batch extends Model
{
    use HasFactory , Notifiable;
    protected $fillable = [
        'name',
        'programme_id',
        'academic_year_id'
    ];
}
