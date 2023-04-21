<?php

namespace App\Http\Controllers;

use App\Models\Bills;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function countUserOrders($userId)
    {
        $user = User::find($userId);
        if(!$user)
        {
            return response()->json([
                'error' => 'User not found!',
            ]);
        }

        $orders = Bills::whereHas('customer', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->pluck('id');//kết quả là 1 Collection chửa mảng các id của bills

        $count = $orders->count();

        return response()->json([
            'bills' => $orders,
            'count' => "Có ".$count." đơn hàng đã được dặt"
        ]);


    }


}
