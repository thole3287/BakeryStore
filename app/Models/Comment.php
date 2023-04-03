<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = "comment";
    protected $primarykey ="id";
    // public $timestamps = true;

    public function customer(){
        return $this->belongsTo(Customer::class, "id", "id");
    }
    public function User(){
        return $this->belongsTo(User::class, "idUser", "id");
    }
}
