<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    use HasFactory;
    protected $table = "bills";
    protected $primarykey ="id";
    public $timestamps = false;
    public function bill_detail()
    {
        return $this->hasMany(Bill_detail::class, "id_bill", "id");
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, "id_customer", "id");
    }

}
