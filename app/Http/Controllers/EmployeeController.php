<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\WorkingTime;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        // $employees = Employee::with('workingTimes')->get();
        $employees = Employee::all();


        return response()->json(['data' => $employees]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'age' => 'required|numberic|max:2',
            'gender' => 'required',
            'image' => 'required',
            'email' => 'required|unique:employees,email',
            'address' => 'required',
            'phone_number' => 'required',
            'job_title' => 'required',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $employee = Employee::create($request->all());
    
        return response()->json(['data' => $employee]);
        
    }

    // public function show($id)
    // {
    //     $employee = Employee::with('workingTimes')->findOrFail($id);
    
    //     return response()->json(['data' => $employee]);
    // }
    // public function show($id)
    // {
    //     $employee = Employee::with('workingTimes')->findOrFail($id);
        
    //     // Get the current month and year
    //     $month = date('m');
    //     $year = date('Y');
        
    //     // Get the first and last day of the month
    //     $firstDay = date('Y-m-d', strtotime("first day of $year-$month"));
    //     $lastDay = date('Y-m-d', strtotime("last day of $year-$month"));
        
    //     // Calculate the total working time in the month
    //     $totalWorkingTime = $employee->workingTimes()
    //         ->whereBetween('date', [$firstDay, $lastDay])
    //         ->sum('total_time');
        
    //     // Format the total working time to h:i:s format
    //     $formattedTotalWorkingTime = gmdate('H:i:s', $totalWorkingTime);
        
    //     // Return the employee data and total working time in the month
    //     return response()->json([
    //         'data' => $employee,
    //         'total_working_time_in_month' => $formattedTotalWorkingTime,
    //     ]);
    // }


    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'age' => 'required|max:2',
            'gender' => 'required',
            'image' => 'required',
            'email' => 'required|unique:employees,email,' . $employee->id,
            'address' => 'required',
            'phone_number' => 'required',
            'job_title' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $employee->update($request->all());

        return response()->json(['data' => $employee]);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->delete();

        return response()->json(['message' => 'Employee deleted successfully']);
    }

    // public function addWorkingTime(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'employee_id' => 'required',
    //         'start_time' => 'required|date_format:Y-m-d H:i:s',
    //         'end_time' => 'required|date_format:Y-m-d H:i:s',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     $employee = Employee::findOrFail($request->input('employee_id'));

    //     $start_time = Carbon::parse($request->input('start_time'));
    //     $end_time = Carbon::parse($request->input('end_time'));

    //     $workingTime = new WorkingTime;
    //     $workingTime->employee_id = $employee->id;
    //     $workingTime->start_time = $start_time;
    //     $workingTime->end_time = $end_time;
    //     $workingTime->total_time = gmdate("H:i:s", $end_time->diffInSeconds($start_time));
    //     $workingTime->save();

    //     return response()->json(['data' => $workingTime]);
    // }
//     public function show(Request $request, $id)
// {
//     $employee = Employee::with('workingTimes')->findOrFail($id);

//     $date = Carbon::parse($request->input('date'));
//     $start_date = $date->copy()->startOfMonth();
//     $end_date = $date->copy()->endOfMonth();

//     $working_times = WorkingTime::where('employee_id', $id)
//         ->whereBetween('start_time', [$start_date, $end_date])
//         ->get();

//     $total_time = $working_times->sum('total_time');

//     return response()->json(['data' => [
//         'employee' => $employee,
//         'total_time' => $total_time
//     ]]);
// }
        
        public function show($id)
        {
            $employee = Employee::with(['workingTimes' => function ($query) {
                $query->whereBetween('start_time', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            }])->findOrFail($id);

            $totalTime = $employee->workingTimes->sum(function ($workingTime) {
                return CarbonInterval::createFromFormat('H:i:s', $workingTime->total_time)->totalSeconds;
            });

            $formattedTotalTime = CarbonInterval::seconds($totalTime)->cascade()->format('%H:%I:%S');

            return response()->json(['data' => $employee, 'total_time_in_month' => $formattedTotalTime]);
        }



    public function addWorkingTime(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $employee = Employee::findOrFail($request->input('employee_id'));

        $workingTime = new WorkingTime;
        $workingTime->employee_id = $employee->id;
        $workingTime->start_time = Carbon::parse($request->input('start_time'));
        $workingTime->end_time = Carbon::parse($request->input('end_time'));
        $workingTime->save();

        return response()->json(['data' => $workingTime]);
    }

    public function calculateWorkingTime(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'date' => 'required|date_format:Y-m-d',
        ]);

        //This if statement checks if the validation has failed. If it has, the function returns a JSON response containing an error message and a status code of 400.
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        //This line retrieves all the WorkingTime records for the specified employee and date.
        $workingTimes = WorkingTime::where('employee_id', $request->input('employee_id'))
            ->whereDate('start_time', $request->input('date'))
            ->get();

        //This code calculates the total working time by iterating through the retrieved WorkingTime records, converting the start and end times to Carbon objects, and adding the difference in minutes to the $totalWorkingTime variable.

        $totalWorkingTime = 0;

        foreach ($workingTimes as $workingTime) {
            $startTime = Carbon::parse($workingTime->start_time);
            $endTime = Carbon::parse($workingTime->end_time);

            $totalWorkingTime += $endTime->diffInMinutes($startTime);
        }

        // This code formats the total working time as a string in the format "HH:MM:SS", and returns it as a JSON response with a status code of 200.

        //calculates the number of hours worked by dividing the total working time by 60 (the number of minutes in an hour) and then taking the floor value. This gives the number of whole hours worked, rounded down to the nearest integer.
        $hours = floor($totalWorkingTime / 60);
        //calculates the number of minutes worked by taking the total working time modulo 60. This gives the remainder after dividing the total working time by 60, which represents the number of minutes remaining after accounting for the whole hours worked.
        $minutes = $totalWorkingTime % 60;

        $formattedTime = sprintf('%02d:%02d:00', $hours, $minutes);

        $workingTime->total_time = $formattedTime;
        $workingTime->save();

        // // Create a new instance of the WorkingTime model
        // $workingTime = new WorkingTime();

        // // Set the total_time attribute to the formattedTime value
        // $workingTime->total_time = $formattedTime;

        // // Set the other attributes as needed
        // $workingTime->employee_id = $request->input('employee_id');
        // $workingTime->start_time = $startTime;
        // $workingTime->end_time = $endTime;

        // Save the model to the database
        // $workingTime->save();

        return response()->json(['data' => ['total_working_time' => $formattedTime]]);
    }


    // public function calculateWorkingTime(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'employee_id' => 'required',
    //         'date' => 'required|date_format:Y-m-d',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     // $workingTimes = WorkingTime::where('employee_id', $request->input('employee_id'))
    //     //     ->whereDate('start_time', $request->input('date'))
    //     //     ->get();
    //     $workingTimes = WorkingTime::where('employee_id', $request->input('employee_id'))
    //                                 ->where('start_time', $request->input('start_time'))
    //                                 ->where('end_time', $request->input('end_time'))
    //                                 ->first();

    //     $totalWorkingTime = Carbon::parse('00:00:00');
    //     // $totalWorkingTime = 0;
    //     // foreach ($workingTimes as $workingTime) {
    //     //     if ($workingTime->end_time) {
    //     //         $totalWorkingTime = $totalWorkingTime->add($workingTime->start_time->diff($workingTime->end_time));
    //     //     } else {
    //     //         $totalWorkingTime = $totalWorkingTime->add($workingTime->start_time->diff(now()));
    //     //     }
    //     // }
    //     foreach ($workingTimes as $workingTime) {
    //         $startTime = Carbon::parse($workingTime->start_time, 'UTC')->setTimezone('Asia/Ho_Chi_Minh');
    //         $endTime = Carbon::parse($workingTime->end_time, 'UTC')->setTimezone('Asia/Ho_Chi_Minh');
        
    //         $totalWorkingTime = $totalWorkingTime->add($endTime->diff($startTime));
    //     }
    //     $formattedTime = $totalWorkingTime->format('H:i:s');
    //     // foreach ($workingTimes as $workingTime) {
    //     //     if ($workingTime->end_time) {
    //     //         $totalWorkingTime = $totalWorkingTime->add($workingTime->start_time->diff($workingTime->end_time));
    //     //     } else {
    //     //         $totalWorkingTime = $totalWorkingTime->add($workingTime->start_time->diff(now()));
    //     //     }
    //     // }
    //     // foreach ($workingTimes as $workingTime) {
    //     //     $startTime = Carbon::parse($workingTime->start_time);
    //     //     $endTime = Carbon::parse($workingTime->end_time);
    
    //     //     $totalWorkingTime += $endTime->diffInMinutes($startTime);
    //     // }
    //     // $totalWorkingTime->format('H:i:s')
    //     return response()->json(['data' => ['total_working_time' => $formattedTime]]);

    //     // return response()->json(['data' => $totalWorkingTime->format('H:i:s')]);

    //     // return response()->json(['data' => ['total_working_time' => $totalWorkingTime]]);
    // }



}
