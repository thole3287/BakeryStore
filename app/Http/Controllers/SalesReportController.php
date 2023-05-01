<?php

namespace App\Http\Controllers;

use App\Models\Bills;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function getTotalSalesPerDayByWeek()
    {
        $sales = DB::table('bills')
                    ->select(DB::raw('DATE(date_order) as date'), DB::raw('WEEK(date_order) as week'), DB::raw('SUM(total) as total_sales'))
                    ->groupBy('date', 'week')
                    ->orderBy('date', 'asc')
                    ->get();
        
        return response()->json($sales);
    }

    public function getTotalSalesPerWeekByMonth()
    {
        $sales = DB::table('bills')
                    ->select(DB::raw('WEEK(date_order) as week'), DB::raw('MONTH(date_order) as month'), DB::raw('SUM(total) as total_sales'))
                    ->groupBy('week', 'month')
                    ->orderBy('month', 'asc')
                    ->get();
        
        return response()->json($sales);
    }

    public function getTotalSalesPerMonthByYear()
    {
        $sales = DB::table('bills')
                    ->select(DB::raw('MONTH(date_order) as month'), DB::raw('YEAR(date_order) as year'), DB::raw('SUM(total) as total_sales'))
                    ->groupBy('month', 'year')
                    ->orderBy('year', 'asc')
                    ->get();
        
        return response()->json($sales);
    }

    public function getTotalSalesByYear($year)
    {  
    //     ->whereYear('date_order', $year)
    //     ->get();

       
        // $sales = DB::table('bills')
        $sales = Bills::select(DB::raw('YEAR(date_order) as year'), DB::raw('SUM(total) as total_sales'))
                    ->whereYear('date_order', $year)
                    ->groupBy('year')
                    ->get();
        if(! $sales)
        {
            return response()->json([
                'message'=>'Year Not Found.'
            ],404);
            
        }
        
        return response()->json($sales);
    }

    public function salesByPeriod()
    {
        // get current date and time
        $now = Carbon::now();
        
        // get sales for each day of the week
        $salesByDayOfWeek = Bills::where('state', 1)->orWhere('state', 2)
            ->whereBetween('date_order', [
                $now->startOfWeek(),
                $now->endOfWeek(),
            ])
            ->selectRaw('DATE(date_order) as date, SUM(total) as total_sales')
            ->groupBy('date')
            ->get();
        // get sales for each week of the month
        $salesByWeekOfMonth = Bills::where('state', 1)->orWhere('state', 2)
                                    ->whereBetween('date_order', [
                                        $now->startOfMonth(),
                                        $now->endOfMonth(),
                                    ])
                                    ->selectRaw('WEEK(date_order) as week, SUM(total) as total_sales')
                                    ->groupBy('week')
                                    ->get();

        // get sales for each day of the month
        $salesByDayOfMonth = Bills::where('state', 1)->orWhere('state', 2)
            ->whereBetween('date_order', [
                $now->startOfMonth(),
                $now->endOfMonth(),
            ])
            ->selectRaw('DATE(date_order) as date, SUM(total) as total_sales')
            ->groupBy('date')
            ->get();

        // get sales for each month of the year
        $salesByMonthOfYear = Bills::where('state', 1)->orWhere('state', 2)
            ->whereYear('date_order', $now->year)
            ->selectRaw('MONTH(date_order) as month, SUM(total) as total_sales')
            ->groupBy('month')
            ->get();

        // get sales for the previous week
        $previousWeek = Carbon::now()->subWeek();
        $salesLastWeek = Bills::where('state', 1)->orWhere('state', 2)
            ->whereBetween('date_order', [
                $previousWeek->startOfWeek(),
                $previousWeek->endOfWeek(),
            ])
            ->sum('total');

        // get sales for the last month
        $previousMonth = Carbon::now()->subMonth();
        $salesLastMonth = Bills::where('state', 1)->orWhere('state', 2)
            ->whereBetween('date_order', [
                $previousMonth->startOfMonth(),
                $previousMonth->endOfMonth(),
            ])
            ->sum('total');

        // get sales for each month of the previous year
        $salesByMonthOfPreviousYear = Bills::where('state', 1)->orWhere('state', 2)
            ->whereYear('date_order', $now->year - 1)
            ->selectRaw('MONTH(date_order) as month, SUM(total) as total_sales')
            ->groupBy('month')
            ->get();

        // // save the results to the database
        // $sales = new Sales();
        // $sales->sales_by_day_of_week = $salesByDayOfWeek;
        // $sales->sales_by_day_of_month = $salesByDayOfMonth;
        // $sales->sales_by_month_of_year = $salesByMonthOfYear;
        // $sales->sales_last_week = $salesLastWeek;
        // $sales->sales_last_month = $salesLastMonth;
        // $sales->sales_by_month_of_previous_year = $salesByMonthOfPreviousYear;
        // $sales->save();

        // return the results as a JSON response
        return response()->json([
            'sales_by_day_of_week' => $salesByDayOfWeek,
            'sales_by_weak_of_month' =>$salesByWeekOfMonth,
            // 'sales_by_day_of_month' => $salesByDayOfMonth,
            'sales_by_month_of_year' => $salesByMonthOfYear,
            'sales_last_week' => $salesLastWeek,
            'sales_last_month' => $salesLastMonth,
            'sales_by_month_of_previous_year' => $salesByMonthOfPreviousYear,
        ]);
    }
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