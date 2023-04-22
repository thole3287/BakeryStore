<?php

namespace App\Http\Controllers;

use App\Models\Bill_detail;
use App\Models\Bills;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    // public function removeProductFromBill(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'billDetailId' => 'required|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['success' => false, 'errors' => $validator->errors()]);
    //     }

    //     $billDetailId = $request->input('billDetailId');

    //     // Get the bill detail by ID
    //     $billDetail = Bill_detail::find($billDetailId);

    //     if (!$billDetail) {
    //         return response()->json(['success' => false, 'message' => 'Bill detail not found.']);
    //     }

    //     // Check the state of the bill detail
    //     if ($billDetail->state == 1) {
    //         return response()->json(['success' => false, 'message' => 'The product cannot be removed because the bill detail is in state 1.']);
    //     }

    //     // Update the stock of the corresponding product
    //     $product = Products::find($billDetail->id_product);
    //     $product->stock += $billDetail->quantity;
    //     $product->save();

    //     // Remove the bill detail
    //     $billDetail->delete();

    //     return response()->json(['success' => true, 'message' => 'Product removed from bill detail successfully.']);
    // }

    public function showtest($id)
    {
        $bill = Bills::find($id);

        if (!$bill) {
            return response()->json(['message' => 'Bill not found'], 404);
        }
        $billDetail = Bill_detail::where("id_bill", "=", $bill->id)
                                    ->join("products", "products.id", "=", "Bill_Detail.id_product")
                                    ->get(['products.id','products.name','bill_detail.quantity', 'bill_detail.unit_price']);
        if(count($billDetail) == 0)
        {
            $bill->delete();
        }
        // $billDetail = Bill_Detail::find($id);

        // if (!$billDetail) {
        //     return response()->json(['success' => false, 'message' => 'Bill detail not found.']);
        // }
        // dd(count($billDetail));
        return response()->json(['bill' => $bill, 'count' => count($billDetail)]);


    }

    // public function removeProductFromBill(Request $request, $billDetailId)
    // {
    //     // try {
    //     //     $user = JWTAuth::parseToken()->authenticate();
    //     // } catch (JWTException $e) {
    //     //     return response()->json(['success' => false, 'message' => 'Failed to authenticate token.']);
    //     // }

    //     $billDetail = Bill_Detail::find($billDetailId);

    //     if (!$billDetail) {
    //         return response()->json(['success' => false, 'message' => 'Bill detail not found.']);
    //     }


    //     $bill = Bills::find($billDetail->id_bill);

    //     if ($bill->state == 1) {
    //         return response()->json(['success' => false, 'message' => 'Cannot delete product from a confirmed bill.']);
    //     }



    //     // Increase product quantities in stock
    //     $product = Products::find($billDetail->id_product);
    //     $product->stock += $billDetail->quantity;
    //     $product->save();

    //     $billDetail->delete();

    //     return response()->json(['success' => true, 'message' => 'Product removed from bill successfully.']);
    // }
    public function removeProductFromBill(Request $request, $billDetailId)
    {
        // try {
        //     $user = JWTAuth::parseToken()->authenticate();
        // } catch (JWTException $e) {
        //     return response()->json(['success' => false, 'message' => 'Failed to authenticate token.']);
        // }

        $billDetail = Bill_Detail::find($billDetailId);

        if (!$billDetail) {
            return response()->json(['success' => false, 'message' => 'Bill detail not found.']);
        }

        $bill = Bills::find($billDetail->id_bill);

        if ($bill->state == 1 || $bill->state == 2) {
            return response()->json(['success' => false, 'message' => 'Cannot delete product from a confirmed bill.']);
        }

        // Increase product quantities in stock
        $product = Products::find($billDetail->id_product);
        $product->stock += $billDetail->quantity;
        $product->save();

        $billDetail->delete();

        // Check if there are any other bill details for the bill
        $billDetailsCount = Bill_Detail::where('id_bill', $bill->id)->count();

        if ($billDetailsCount == 0) {
            $bill->delete();
            return response()->json(['success' => true, 'message' => 'Bill deleted successfully.']);
        }

        return response()->json(['success' => true, 'message' => 'Product removed from bill successfully.']);
    }





}
