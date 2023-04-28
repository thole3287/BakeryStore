<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $table = 'sales';

    protected $fillable = [
        'sales_by_day_of_week',
        'sales_by_day_of_month',
        'sales_by_month_of_year',
        'sales_last_week',
        'sales_last_month',
        'sales_by_month_of_previous_year'
    ];

    protected $casts = [
        'sales_by_day_of_week' => 'array',
        'sales_by_day_of_month' => 'array',
        'sales_by_month_of_year' => 'array',
        'sales_by_month_of_previous_year' => 'array'
    ];
}
