<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;
    protected $table = "slide";
    protected $primarykey ="id";
    public $timestamps = false;

    protected $fillable = [
        'name', 
        'link',
        'image', 
    ];
    
}
