<?php

namespace App\Http\Controllers;

use App\Models\Bills;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function salesReport()
    {
       // Get the current date and time
        $now = Carbon::now();

        // Calculate the date for one week ago
        $oneWeekAgo = $now->copy()->subWeek();

        // Calculate the date for one month ago
        $oneMonthAgo = $now->copy()->subMonth();

        // Calculate the date for one year ago
        $oneYearAgo = $now->copy()->subYear();

        // Query for the total sales for each day of the week
        $dailySalesOfWeek = DB::table('bills')
            ->select(DB::raw('DATE(date_order) AS date'), DB::raw('SUM(total) AS total_sales'))
            ->whereBetween('date_order', [$oneWeekAgo, $now])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Query for the total sales for each day of the month
        $dailySalesOfMonth = DB::table('bills')
            ->select(DB::raw('DATE(date_order) AS date'), DB::raw('SUM(total) AS total_sales'))
            ->whereBetween('date_order', [$oneMonthAgo, $now])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Query for the total sales for each month of the year
        $monthlySalesOfYear = DB::table('bills')
            ->select(DB::raw('YEAR(date_order) AS year'), DB::raw('MONTH(date_order) AS month'), DB::raw('SUM(total) AS total_sales'))
            ->whereBetween('date_order', [$oneYearAgo, $now])
            ->groupBy('year', 'month')
            ->orderBy('year', 'month')
            ->get();

        // Query for the total sales for each day of the previous week
        $dailySalesOfPreviousWeek = DB::table('bills')
            ->select(DB::raw('DATE(date_order) AS date'), DB::raw('SUM(total) AS total_sales'))
            ->whereBetween('date_order', [$oneWeekAgo->copy()->subWeek(), $oneWeekAgo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Query for the total sales for each day of the last month
        $dailySalesOfLastMonth = DB::table('bills')
            ->select(DB::raw('DATE(date_order) AS date'), DB::raw('SUM(total) AS total_sales'))
            ->whereBetween('date_order', [$oneMonthAgo->copy()->subMonth(), $oneMonthAgo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Query for the total sales for each month of the previous year
        $monthlySalesOfPreviousYear = DB::table('bills')
            ->select(DB::raw('YEAR(date_order) AS year'), DB::raw('MONTH(date_order) AS month'), DB::raw('SUM(total) AS total_sales'))
            ->whereBetween('date_order', [$oneYearAgo->copy()->subYear(), $oneYearAgo])
            ->groupBy('year', 'month')
            ->orderBy('year', 'month')
    ->get();

        // Return the sales data in JSON
        return response()->json([
            'daily_sales_of_week' => $dailySalesOfWeek,
            'daily_sales_of_month' => $dailySalesOfMonth,
            'monthly_sales_of_year' => $monthlySalesOfYear,
            'daily_sales_of_previous_week' => $dailySalesOfPreviousWeek,
            'daily_sales_of_last_month' => $dailySalesOfLastMonth,
            'monthly_sales_of_previous_year' => $monthlySalesOfPreviousYear,
        ]);
    }
}