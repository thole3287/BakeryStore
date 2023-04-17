<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NO_NASTIES extends Model
{
    use HasFactory;
    protected $table = "no_nasties";
    protected $primarykey ="id";
    protected $fillable = [
        'name', 
        'description',
        'image'
    ];

}
