<?php
     namespace App\Models;
class Cart 
{
    public $products = null;
    public $totalPrice = 0;
	public $totalQty = 0;
	
    public function __construct($cart){
		if($cart){
			$this->products = $cart->products;
			$this->totalQty = $cart->totalQty;
			$this->totalPrice = $cart->totalPrice;
		}
	}
    public function addCart($products, $id)
    {
        $newProduct = ['quanty' => 0, 'price' => ($products->promotion_price==0)? $products->unit_price : $products->promotion_price, 'productInfo' => $products];
        if ($this->products) {
            if(array_key_exists($id, $products))// nếu id có r thì tồn tại
            {
                $newProduct = $products[$id];// nếu tồn tại thì đặt lại bằng chính danh sách của mình úng vs id truyền vào
                
            }
        } 
        $newProduct['quanty']++;
        $newProduct['productInfo'];
    }
}
    
?>