<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'job_title',
        'email', 
        'address', 
        'image',
        'age',
        'gender',
        'phone_number'
    ];
    public function workingTimes()
    {
        return $this->hasMany(WorkingTime::class , 'employee_id', 'id');
    }
   

    
}
