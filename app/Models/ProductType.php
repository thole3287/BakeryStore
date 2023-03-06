<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;
    protected $table = "type_products";
    protected $primarykey ="id";
    public $timestamps = false;
    public function product()
    {
        return $this->hasMany(Products::class, "id_type", "id");
    }

    
}
