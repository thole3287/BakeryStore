<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Mail\CancelOrder;
use App\Models\Bill_detail;
use App\Models\Bills;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Products;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CartController extends Controller 
{
    public function __construct()
    {
        $this->middleware('web');
    }

    public function orderList()
    {
        $orders = Bills::join('customer', 'bills.id_customer', '=', 'customer.id')
            ->select('bills.*', 'customer.name as customer_name', 'customer.email as customer_email', 'customer.address as customer_address', 'customer.phone_number as customer_phone_number')
            ->get();

        return response()->json([
                'order' => $orders
            ]);
    }
    public function show($id)
    {
        $bill = Bills::find($id);

        if (!$bill) {
            return response()->json(['message' => 'Bill not found'], 404);
        }

        $customer = Customer::find($bill->id_customer);
        $billDetail = Bill_detail::where("id_bill", "=", $bill->id)
                                    ->join("products", "products.id", "=", "Bill_Detail.id_product")
                                    ->get(['products.id','products.name','bill_detail.quantity', 'bill_detail.unit_price']);

        return response()->json(compact("bill", "customer", "billDetail"));
    }
    public function sendOrderCancellationEmail($bill)
    {
        $customer = $bill->customer;
        $items = $bill->bill_detail;

        Mail::to($customer->email)->send(new CancelOrder($customer, $items, $bill));
    }

    public function deleteBill($id)
    {
        // try {
        //     $user = JWTAuth::parseToken()->authenticate();
        // } catch (JWTException $e) {
        //     return response()->json(['success' => false, 'message' => 'Failed to authenticate token.']);
        // }
        $bill = Bills::find($id);

        if (!$bill) {
            return response()->json(['message' => 'Bill not found'], 404);
        }

        $billDetail = Bill_detail::where('id_bill', '=', $bill->id)->get();

        foreach ($billDetail as $detail) {
            $product = Products::find($detail->id_product);
            $product->stock += $detail->quantity;
            $detail->delete();
            $product->save();
        }

        // Send cancellation email
        $this->sendOrderCancellationEmail($bill);

        $bill->delete();

        return response()->json(['message' => 'Bill cancelled successfully'], 200);
    }

    public function sendOrderConfirmationEmail($customer, $bill, $items)
    {
        Mail::to($customer->email)->send(new OrderPlaced($customer, $bill, $items));
    }

    public function orderItems(OrderRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Failed to authenticate token.']);
        }
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'total' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }
    
    
        $items = $request->input('items');
        // dd($items);
        $total = $request->input('total');
       
     
        $cus = new Customer();
        $cus->name = $request->name;
        $cus->user_id = auth()->user()->id;
        // $cus->gender = $request->gender;
        $cus->email = $request->email;
        $cus->address = $request->address;
        $cus->phone_number = $request->phone;
        $cus->note = $request->note;
        $cus->save();

        //save information of bill
        $bill = new Bills();
        $bill->id_customer = $cus->id;
        $bill->date_order = date('Y-m-d');
        $bill->total = $total;
        $bill->payment = $request->input('payment');
        $bill->note = $request->input('note');
        $bill->save();

        //save order details
        foreach ($items as $item) {
            $bd = new Bill_Detail();
            $bd->id_bill = $bill->id;
            $bd->id_product = $item["productId"];
            $bd->quantity = $item["quantity"];
            $bd->unit_price = $item["price"] / $item["quantity"];
            $bd->save();

            // Deduct product quantities from stock
            $product = Products::find($item["productId"]);
            $product->stock -= $item["quantity"];
            $product->save();
        }

        // Send confirmation email
        $this->sendOrderConfirmationEmail($cus, $bill, $items);

        return response()->json([
            'success' => true, 
            'message' => 'Order placed successfully!'
        ]);
    }
   
    public function postOrderUpdate(Request $req, $id)
    {
        $bill= Bills::find($id);
        $bill->state = $req->state;
        $bill->save();
        return response()->json(['message' => 'Order update successfully !!!']);
    }



   
}