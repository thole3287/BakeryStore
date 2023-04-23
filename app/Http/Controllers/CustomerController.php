<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::all();
        return response()->json([
           'customer' => $customer 
        ]);

    }

    public function show($id)
    {
       $customer = Customer::find($id);

        return response()->json([
                    "customer" =>$customer
                    ]);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        if(!$customer){
            return response()->json([
            'message'=>'Customer Not Found.'
            ],404);
        }

        $customer->name= $request->name;
        $customer->gender= $request->gender;
        $customer->email= $request->email;
        $customer->address= $request->address;
        $customer->phone_number= $request->phone_number;
        $customer->note= $request->note;
        $customer->save();

        return response()->json([
            'status' => true,
            'customer' => 'Update customer successfully.'
        ]);
    }
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if(! $customer){
            return response()->json([
            'message'=>'Customer not found!'
            ],404);
        }
        $customer->delete();
        return response()->json([
            'status' => true,
            'message' => 'Customer successfully deleted.'
        ]);
    }
}
