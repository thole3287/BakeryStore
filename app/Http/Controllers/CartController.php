<?php

namespace App\Http\Controllers;
use App\Models\Bill_detail;
use App\Models\Bills;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Products;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
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
        $oldCart = Session('cart')? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($products, $id);
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


   
}
