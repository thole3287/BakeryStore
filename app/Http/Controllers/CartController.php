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
    public function sendOrderConfirmationEmail($customer, $bill, $cart)
    {
        Mail::to($customer->email)->send(new OrderPlaced($customer, $bill, $cart));
    }

    public function showCart()
    {
        $cart = Session::get('cart');
        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }
        return response()->json($cart);
    }
    
    public function addToCart(Request $req, $id)
    {
        // // Find the product by its ID
        $products = Products::where('id', '=', $id)->first();
          // Check if the item exists
         if (!$products) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        // if ($products->stock < 1) {
        //     return response()->json(['error' => 'Insufficient stock'], 400);
        // }

        
        $oldCart = Session('cart')? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        // if($products->stock <= 0 && )
        // {
        //     return response()->json(['error' => 'Insufficient stock'], 400);
        // }
       // Check if the product can be added to the cart
        if(!$cart->add($products, $id)){
            return response()->json(['message' => 'Product out of stock'], 400);
        }
        // $cart->add($products, $id);
        $req->session()->put('cart', $cart);
        return response()->json([
            'message' => 'Product added to cart successfully.',
            // 'cart' => $cart,
            'cart'=>  $cart
        ]);
        
        // if($products !=null)
        // {
        //     $oldCart = Session('cart') ? Session('cart') :null;
        //     $newCart = new Cart($oldCart);
        //     $newCart->add($products, $id);
        //     // dd($newCart);
        //     $req->session()->put('cart', $newCart);

        //     return response()->json(['message' => 'Product added to cart successfully.']);
        //     // dd(Session('cart'));
        // }
       
        // return response()->json(['success' => false]);
        
        // return response()->json([
        //     'message' => 'Product added to cart successfully.',
        //     // 'cart' => $cart,
        //     'newCart'=>  $newCart
        // ]);
         // Check if the item exists
        //  if (!$products) {
        //     return response()->json(['message' => 'Item not found'], 404);
        // }
        //  // Get the current cart from the session, or create a new one if there is none
        // //   $oldCart = $req->session()->get('cart');
        // $oldCart = Session('cart')? Session::get('cart') : null;
        // $cart = new Cart($oldCart);
        // // Add the product to the cart
        // $cart->add($products, $id);
        //  // Store the updated cart back in the session
        // $req->session()->put('cart', $cart);
        // // Return a success response with the updated cart data
       
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

    public function saveAllListItemCart(Request $req)
    {
       
    }
    public function orderItems(Request $req)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Failed to authenticate token.']);
        }

        $cart = Session::get('cart');
        
        //save information customer
        $cus = new Customer();
        $cus->name = $req->name;
        $cus->gender = $req->gender;
        $cus->email = $req->email;

        $cus->address= $req->address;
        $cus->phone_number = $req->phone_number;
        $cus->note = $req->note;
        $cus->save();

        //save information of bill
        $bill = new Bills();
        $bill->id_customer = $cus->id;
        $bill->date_order = date('Y-m-d');
        $bill->total = $cart->totalPrice;
        $bill->payment = $req->payment;
        $bill->note = $req->note;
        // $bill->state = $req->state;
        $bill->save();
        //save order details
        foreach($cart->items as $key => $value)
        {
            $bd = new Bill_Detail();
            $bd->id_bill = $bill->id;
            $bd->id_product = $key;
            $bd->quantity = $value["qty"];
            $bd->unit_price = ($value["price"]/$value["qty"]);
            $bd->save();
        }
        // Deduct product quantities from stock
        foreach($cart->items as $key => $value)
        {
            $product = Products::find($key);
            $product->stock -= $value["qty"];
            $product->save();
        }
         // Send confirmation email
        // $this->sendOrderConfirmationEmail($cus, $bill, $cart);
        // Clear cart
        Session::forget('cart');
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
