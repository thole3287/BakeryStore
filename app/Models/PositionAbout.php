<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionAbout extends Model
{
    use HasFactory;

    protected $table = "position";
    protected $primarykey ="id";
    protected $fillable = [
        'name', 
        'description',
        'image'
    ];
}
