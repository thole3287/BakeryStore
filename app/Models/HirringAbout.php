<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HirringAbout extends Model
{
    use HasFactory;
    protected $table = "hirring";
    protected $primarykey ="id";
    protected $fillable = [
        'title', 
        'description',
        'image'
    ];
}
