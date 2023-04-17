<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingTime extends Model
{
    use HasFactory;
    protected $fillable = ['employee_id', 'start_time', 'end_time', 'total_time'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
