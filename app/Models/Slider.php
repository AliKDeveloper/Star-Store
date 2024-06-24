<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    // Table Name in Database
    protected $table = 'sliders';

    protected $fillable = [
        'name',
        'image',
    ];
}
