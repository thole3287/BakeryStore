<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill_detail extends Model
{
    use HasFactory;
    protected $table = "bill_detail";
    protected $primarykey ="id";
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Products::class, "id_product", "id");
    }
    public function bill()
    {
        return $this->belongsTo(Bills::class, "id_bill", "id");
    }
    // public function product()
    // {
    //     return $this->belongsTo(Products::class, " id_product", "id");
    // }
}
