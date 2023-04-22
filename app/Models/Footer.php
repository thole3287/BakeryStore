<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;
    protected $table = "footer";
    protected $primarykey ="id";
    protected $fillable = [
        'title_1', 
        'description_1',
        'title_2', 
        'description_2',
        'title_branch', 
        'description_branch',
        'title_3', 
        'description_3',
        'title_4', 
        'description_4',
        'title_5', 
        'description_5',
    ];
}
