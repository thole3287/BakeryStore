<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forte extends Model
{
    use HasFactory;
    protected $table = "forte";
    protected $primarykey ="id";
    protected $fillable = [
        'name', 
        'description',
        'image'
    ];
}
