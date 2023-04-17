<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQS extends Model
{
    use HasFactory;
    protected $table = "faqs";
    protected $primarykey ="id";
    protected $fillable = [
        'name', 
        'description',
    ];
}
