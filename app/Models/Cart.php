<?php

namespace App\Models;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
App::bind('Acme\Interfaces\HelpersInterface', 'helpers');
class Cart
{
	public $items = null;
	public $totalQty = 0;
	public $totalPrice = 0;
	public function __construct($oldCart){
		if($oldCart){
			$this->items = $oldCart->items;
			$this->totalQty = $oldCart->totalQty;
			$this->totalPrice = $oldCart->totalPrice;
		}
	}
	public function add($item, $id){
		$cart = ['qty'=>0, 'price' => ($item->promotion_price==0)? $item->unit_price : $item->promotion_price, 'item' => $item];
		if($this->items){
			if(array_key_exists($id, $this->items)){
			$cart = $this->items[$id];
			}
		}
		$cart['qty']++;
		$cart['price'] = (($item->promotion_price==0)? $item->unit_price : $item->promotion_price) * $cart['qty'];
		$this->items[$id] = $cart;
		$this->totalQty++;
		$this->totalPrice += ($item->promotion_price==0) ? $item->unit_price : $item->promotion_price;
	}
	//xóa 1
	public function reduceByOne($id){
		$price = $this->items[$id]['price'] / $this->items[$id]['qty'];
		$this->items[$id]['qty']--;
		$this->items[$id]['price'] -= $price;
		$this->totalQty--;
		$this->totalPrice -= $price;
		if($this->items[$id]['qty']<=0){
			unset($this->items[$id]);
		}
	}
	//xóa nhiều
	public function removeItem($id){
		$this->totalQty -= $this->items[$id]['qty'];
		$this->totalPrice -= $this->items[$id]['price'];
		unset($this->items[$id]);
	}
}