<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    // protected $table = "products";
    // protected $primarykey ="id";
    // public $timestamps = false;

    protected $fillable = [
        'name', 
        'id_type',
        'description',
        'unit_price',
        'promotion_price',
        'image', 
        'unit',
        'new',
    ];
    
    public function product_type()
    {
        return $this->belongsTo(ProductType::class, "id_type", "id");
    }
    public function bill_detail()
    {
        return $this->hasMany(Bill_detail::class, "id_product", "id");
    }
}
