<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About_Baker extends Model
{
    use HasFactory;

    protected $table = "about_baker";
    protected $primarykey ="id";
    protected $fillable = [
        'title', 
        'description_1',
        'description_2'
    ];
}
