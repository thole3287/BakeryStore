<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = "branch";
    protected $primarykey ="id";
    protected $fillable = [
        'name',
        'address',
        'time_open',
        'image'
    ];
}
