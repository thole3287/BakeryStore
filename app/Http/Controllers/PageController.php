<?php

namespace App\Http\Controllers;

use App\Models\Bill_detail;
use App\Models\Products;
use App\Models\ProductType;
use Illuminate\Http\Request;

class PageController extends Controller
{

    // public function getProductByType($type_id, Request $req)
    // {

    //     $index = $req->query('index') ;
    //     if($index == null)
    //     {
    //         $index = 1;
    //     }
    //     $pageSize = $req->query('size');
    //     if($pageSize == null)
    //     {
    //         $pageSize = 10;
    //     }
    //     $sortField = $req->query('sort');
    //     if($sortField == null)
    //     {
    //         $sortField = 'name';
    //     }
    //     // $type = ProductType::where('id','=',$id_type)->first();
    //     // $categories = ProductType::all();
    //     // echo $sortField;
    //     $productByType = Products::where('id_type','=', $type_id)->orderBy($sortField)->skip(($index - 1)* $pageSize)->take($pageSize)->get();

    //     return response()->json([
    //         "productByType" => $productByType
    //             ]);

    //     // $query = $req->all();


    // }

    

    public function getProductsDetail($id)
    {
        $productsDetail = Products::where('id','=',$id)->first();
        return response()->json([
            "productsDetail" => $productsDetail
            ]);
    }
    public function relatedProducts($id)
    {
        $productsDetail = Products::where('id','=',$id)->first();
        $relatedProducts = Products::where('id_type', '=', $productsDetail->id_type)->paginate(5);
        return response()->json([
            'relatedProduct' =>  $relatedProducts
        ]);
    }
    public function newProducts()
    {
        $newProducts = Products::where('new', '=', 1)->take(8)->get();
        return response()->json([
            'newProducts' =>  $newProducts
        ]);

    }
    public function sellingProducts()
    {
        // $sellingProducts = Bill_detail::selectRaw('id_product, sum(quantity) as total')
        //                                 ->groupBy('id_product')
        //                                 ->orderByDesc('total')->take(2)->get();
        // $sellingProducts = Bill_detail::selectRaw('products.name, sum(quantity) as total')
        //                                     ->join('products', 'bill_detail.id_product', '=', 'products.id')
        //                                     ->groupBy('bill_detail.id_product')
        //                                     ->orderByDesc('total')->take(2)->get();
        $sellingProducts = Bill_detail::selectRaw('id_product, sum(quantity) as total')
                                            ->with('product')
                                            ->groupBy('id_product')
                                            ->orderByDesc('total')
                                            ->take(2)
                                            ->get();
        return response()->json([
            'sellingProducts' =>  $sellingProducts
        ]);                                
    }

    public function getSearch(Request $req)
    {
        // $keySearch = $req->query('key') ;
        $searchProduct = Products::where("name", "like", "%".$req->query('key')."%")
                            ->orWhere("unit_price", $req->query('key'))
                            ->get();

        return response()->json([
            "searchProduct" =>  $searchProduct
            ]);
    }


}
