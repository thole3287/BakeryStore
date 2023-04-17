<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Our_Kitchen extends Model
{
    use HasFactory;

    protected $table = "our_kitchen";
    protected $primarykey ="id";
    protected $fillable = [
        'name', 
        'description',
        'image'
    ];
}
