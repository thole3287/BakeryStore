<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductTypeRequest;
use App\Models\Products;
use App\Models\ProductType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductsTypeController extends Controller
{
    public function index() {
        $productByType = ProductType::all();
        return response()->json([
            "productByType" => $productByType
            ]);
    }

    public function show($type_id)
    {
        $productsByType = Products::where('id_type','=', $type_id)->get();
        return response()->json([
                    "productByType" => $productsByType
                    ]);
    }

    public function store(ProductTypeRequest $req)
    {
        try {

            $imageName = Str::random(32).".".$req->image->getClientOriginalExtension();
     
            // Create Product
            ProductType::create([
                'name' => $req->name,
                'description' => $req->description,
                'image' => $imageName,
            ]);
     
            // Save Image in Storage folder
            $path = 'uploads/products/' . $imageName;
            Storage::disk('public')->put($path, file_get_contents($req->image));
     
            // Return Json Response
            return response()->json([
                'message' => "Product Type successfully created."
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }

    public function update(ProductTypeRequest $req, $id)
    {
        try {
            // Find product
            $product = Products::find($id);
            if(!$product){
              return response()->json([
                'message'=>'Product Type Not Found.'
              ],404);
            }
     
            $product->name = $req->name;
            $product->description = $req->description;
            if($req->image) {
                // Public storage
                $storage = Storage::disk('public');
     
                // Old iamge delete
                if($storage->exists($product->image))
                    $storage->delete($product->image);
     
                // Image name
                $imageName = Str::random(32).".".$req->image->getClientOriginalExtension();
                $product->image = 'uploads/products/'.$imageName; // specify the subfolder path
     
                // Image save in public folder
                $storage->put('uploads/products/'.$imageName, file_get_contents($req->image));// specify the subfolder path
            }
     
            // Update Product
            $product->save();
     
            // Return Json Response
            return response()->json([
                'message' => "Product Type successfully updated."
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }

    public function destroy($id)
    {
        // Detail 
        $product = ProductType::find($id);
        if(!$product){
          return response()->json([
             'message'=>'Product Type Not Found.'
          ],404);
        }

        if(count($product->product)>=1)
        {
             // Return Json Response
             return response()->json([
                'message' => "Delete failed Product Type!!"
            ],200);
        }
        else
        {
                // Public storage
            $storage = Storage::disk('public');
        
            // Iamge delete
            if($storage->exists('uploads/products/'.$product->image)) // specify the subfolder path
                $storage->delete('uploads/products/'.$product->image);// specify the subfolder path
        
            // Delete Product
            $product->delete();
        
            // Return Json Response
            return response()->json([
                'message' => "Product Type successfully deleted."
            ],200);
        }
     
       
    }
}