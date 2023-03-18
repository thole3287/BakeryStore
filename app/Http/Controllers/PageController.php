<?php

namespace App\Http\Controllers;
use App\Models\Products;
use App\Models\ProductType;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function getProductIndex()
    {
        return Products::get();
    }

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
    
    public function getProductsByType($type_id)
    {
        $productsByType = Products::where('id_type','=', $type_id)->get();
        return response()->json([
                    "productByType" => $productsByType
                    ]);
    }

    public function getProductsType() {
        return ProductType::get();
    }

    public function getProductsDetail($id)
    {
        $productsDetail = Products::where('id','=',$id)->first();
        return response()->json([
            "productsDetail" => $productsDetail
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
