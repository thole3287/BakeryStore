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

    public function getProductByType($type_id, Request $req)
    {
        
        $index = $req->query('index') ;
        if($index == null)
        {
            $index = 1;
        }
        $pageSize = $req->query('size');
        if($pageSize == null)
        {
            $pageSize = 10;
        }
        $sortField = $req->query('sort');
        if($sortField == null)
        {
            $sortField = 'name';
        } 
        // $type = ProductType::where('id','=',$id_type)->first();
        // $categories = ProductType::all();
        // echo $sortField;
        $productByType = Products::where('id_type','=', $type_id)->orderBy($sortField)->skip(($index - 1)* $pageSize)->take($pageSize)->get();

        return response()->json([
            "productByType" => $productByType
                ]);
        
        // $query = $req->all();


    }
}
