<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = "customer";
    protected $primarykey ="id";
    public $timestamps = false;

    public function bill()
    {
        return $this->hasMany(Bills::class, "id_customer", "id");
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
