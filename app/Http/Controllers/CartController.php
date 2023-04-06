<?php

namespace App\Http\Controllers;
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
use Illuminate\Support\Facades\Validator;


class CartController extends Controller 
{
    public function __construct()
    {
        $this->middleware('web');
    }

    public function orderList()
    {
        $order = Bills::join('Customer','Customer.id','=', 'bills.id_customer')->get();
        return response()->json([
            'order' => $order,
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

    // public function delete($id)
    // {
    //     $bill = Bills::find($id);

    //     if (!$bill) {
    //         return response()->json(['message' => 'Bill not found'], 404);
    //     }

    //     $bill->delete();

    //     return response()->json(['message' => 'Bill deleted successfully'], 200);
    // }

    public function deleteBill($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Failed to authenticate token.']);
        }
        $bill = Bills::find($id);

        if (!$bill) {
            return response()->json(['message' => 'Bill not found'], 404);
        }

        $billDetail = Bill_detail::where('id_bill', '=', $bill->id)->get();

        foreach ($billDetail as $detail) {
            $product = Products::find($detail->id_product);
            $product->stock += $detail->quantity;
            $product->save();
            $detail->delete();
        }

        $bill->delete();

        return response()->json(['message' => 'Bill deleted successfully'], 200);
    }

    // public function deleteBills($id)
    // {
    //     $bill = Bills::find($id);

    //     if (!$bill) {
    //         return response()->json(['message' => 'Bill not found'], 404);
    //     }

    //     $billDetail = Bill_detail::where('id_bill', '=', $bill->id)->delete();
    //     $bill->delete();

    //     return response()->json(['message' => 'Bill and its details deleted successfully']);
    // }

    // public function delete($id)
    // {
    //     $bill = Bills::find($id);

    //     if (!$bill) {
    //         return response()->json(['message' => 'Bill not found'], 404);
    //     }

    //     // Bill_detail::where('id_bill', $bill->id)->delete(); // delete all bill_detail records associated with the bill
    //     Bill_detail::where('id_bill', $bill->id)->delete(); // delete all bill_detail records associated with the bill

    //     // Increase product stock
    //     $product = Products::find($billDetail->id_product);
    //     $product->stock += $billDetail->quantity;
    //     $product->save();
    //     $bill->delete();

    //     return response()->json(['message' => 'Bill and associated details deleted successfully'], 200);
    // }


    public function cancelOrderItem($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Failed to authenticate token.']);
        }
        $billDetail = Bill_Detail::find($id);
        if (!$billDetail) {
            return response()->json(['message' => 'Order item not found'], 404);
        }
        // dd( $billDetail );
        

        // Increase product stock
        $product = Products::find($billDetail->id_product);
        $product->stock += $billDetail->quantity;
        $product->save();

        // Delete order item
        $billDetail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order item cancelled successfully!',
        ]);
    }


    public function removeOrderList($id)
    {
        $bills = Bills::find($id);
        if(!$bills){
          return response()->json([
             'message'=>'Bill Not Found.'
          ],404);
        }
        // $bills = Bills::join('Customer','Customer.id','=', 'bills.id_customer');
        $bills->delete();
        return response()->json([
            'message' => 'Delete bill of product successfully!'
        ]);
    }

    public function sendOrderConfirmationEmail($customer, $bill, $cart)
    {
        Mail::to($customer->email)->send(new OrderPlaced($customer, $bill, $cart));
    }

    public function showCart(Request $request)
    {
        $cart = new Cart(json_decode($request->cookie('cart'), true));
        return response()->json([
            'items' => $cart->items,
            'totalQty' => $cart->totalQty,
            'totalPrice' => $cart->totalPrice
        ]);
    }
    
    public function addToCart(Request $request, $id)
    {
        $this->middleware('jwt.verify'); // add JWT middleware
        $product = Products::find($id);
        if(!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $cart = json_decode($request->cookie('cart'), true);

        $cart = new Cart($cart);

        if($cart->add($product, $product->id)) {
            return response()->json(['message' => 'Product added to cart successfully'], 200)
                ->cookie('cart', json_encode($cart), 60*24*7); // set cookie for 7 days
        } else {
            return response()->json(['error' => 'Product is out of stock or already in cart'], 400);
        }
    }

    public function deleteItemCart(Request $req, $id)
    {
        try {
            $oldCart = $req->session()->get('cart') ? $req->session()->get('cart') : null;
            $cart = new Cart($oldCart);
            $cart->reduceByOne($id);
            if(count($cart->items)>0)
            {
                $req->session()->put('cart', $cart);
            }else
            {
                $req->session()->forget('cart');
            }
            return response()->json([
                'success' => true,
                'message' => 'Delete product from cart successfully!'
            ]);
        } catch (\Exception $e) {
             // Return Json Response
             return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }

    public function deleteItemAllCart(Request $req, $id)
    {
        try {
            $oldCart = $req->session()->get('cart') ? $req->session()->get('cart') : null;
            $cart = new Cart($oldCart);
            $cart->removeItem($id);
            if(count($cart->items)>0)
            {
                $req->session()->put('cart', $cart);
            }else
            {
                $req->session()->forget('cart');
            }
            return response()->json([
                'message' => 'Delete all product from cart successfully!'
            ]);
        } catch (\Exception $e) {
             // Return Json Response
             return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }

    public function saveListItemCart(Request $req, $id)
    {
       try {
            $quanty = $req->query('quanty') ;
            if($quanty == null)
            {
                $quanty = 1;
            }
            $oldCart = $req->session()->get('cart') ? $req->session()->get('cart') : null;
            $cart = new Cart($oldCart);
            $cart->updateItemCart($id, intval($quanty));
            if(count($cart->items)>0)
            {
                $req->session()->put('cart', $cart);
            }else
            {
                $req->session()->forget('cart');
            }
            return response()->json([
                'message' => 'Save list Item to cart successfully!'
            ]);
       } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
       }
    }

    public function clearCart()
    {
        session(['cart' => '']);
        return response()->json([
            'success' => true,
            'message' => 'Remove all product in cart successfully!'
        ]);
    }

    public function orderItems(Request $request)
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
        $cus->name = $request->input('name');
        $cus->user_id = auth()->user()->id;
        $cus->gender = $request->input('gender');
        $cus->email = $request->input('email');
        $cus->address = $request->input('address');
        $cus->phone_number = $request->input('phone');
        $cus->note = $request->input('note');
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
        // $this->sendOrderConfirmationEmail($cus, $bill, $items);

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
