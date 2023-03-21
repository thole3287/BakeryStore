<?php

namespace App\Http\Controllers;
use App\Models\Products;
use App\Http\Requests\ProductStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function Index()
    {
        // All Product
       $products = Products::all();
     
       // Return Json Response
       return response()->json([
          'products' => $products
       ],200);
        
    }

    public function store(ProductStoreRequest $req)
    {
        try {

            $imageName = Str::random(32).".".$req->image->getClientOriginalExtension();
     
            // Create Product
            Products::create([
                'name' => $req->name,
                'id_type' => $req->id_type,
                'description' => $req->description,
                'unit_price' => $req->unit_price,
                'promotion_price' => $req->promotion_price,
                'image' => $imageName,
                'unit' => $req->unit,
                'new' => $req->new
            ]);
     
            // Save Image in Storage folder
            $path = 'uploads/products/' . $imageName;
            Storage::disk('public')->put($path, file_get_contents($req->image));
     
            // Return Json Response
            return response()->json([
                'message' => "Product successfully created."
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }

    public function show($id)
    {
       // Product Detail 
       $product = Products::find($id);
       if(!$product){
         return response()->json([
            'message'=>'Product Not Found.'
         ],404);
       }
     
       // Return Json Response
       return response()->json([
          'product' => $product
       ],200);
    }

    public function update(ProductStoreRequest $req, $id)
    {
        try {
            // Find product
            $product = Products::find($id);
            if(!$product){
              return response()->json([
                'message'=>'Product Not Found.'
              ],404);
            }
     
            $product->name = $req->name;
            $product->description = $req->description;

            $product->id_type = $req->id_type;
            $product->unit_price = $req->unit_price;
            $product->promotion_price = $req->promotion_price;
            $product->unit = $req->unit;
            $product->new = $req->new;

                // 'description' => $req->description,
                // 'unit_price' => $req->unit_price,
                // 'promotion_price' => $req->promotion_price,
                // 'image' => $imageName,
                // 'unit' => $req->unit,
                // 'new' => $req->new

            
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
                'message' => "Product successfully updated."
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
        $product = Products::find($id);
        if(!$product){
          return response()->json([
             'message'=>'Product Not Found.'
          ],404);
        }
     
        // Public storage
        $storage = Storage::disk('public');
     
        // Iamge delete
        if($storage->exists('uploads/products/'.$product->image)) // specify the subfolder path
            $storage->delete('uploads/products/'.$product->image);// specify the subfolder path
     
        // Delete Product
        $product->delete();
     
        // Return Json Response
        return response()->json([
            'message' => "Product successfully deleted."
        ],200);
    }
}
