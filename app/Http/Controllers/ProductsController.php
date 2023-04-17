<?php

namespace App\Http\Controllers;
use App\Models\Products;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\TypeOfProductRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            // $unit = $req->filled('unit') ? $req->unit : null;

            // // Read image data
            // $imageData = file_get_contents($req->image);

            // // Convert compressed data to base64
            // $base64Data = base64_encode($imageData);
            
            // Check if promotional price is not greater than original price
            if ($req->promotion_price >= $req->unit_price) {
            return response()->json([
                'message' => "The promotional price cannot be greater than the original price."
            ], 422);
            }


            
            // Create Product
            Products::create([
                'name' => $req->name,
                'id_type' => $req->id_type,
                'description' => $req->description,
                'unit_price' => $req->unit_price,
                'promotion_price' => $req->promotion_price,
                // 'image' => $base64Data, // store base64-encoded compressed image data
                'image' => $req->image, 
                'stock' => $req->stock,
                'unit' => $req->unit,
                'new' => $req->new
            ]);
            
            // Return Json Response
            return response()->json([
                'message' => "Type of product created successfully!",
                // 'image' => $base64Data
            ],200);
            } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }
    // public function store(ProductStoreRequest $req)
    // {
    //     try {

    //         $imageName = Str::random(32).".".$req->image->getClientOriginalExtension();
    //         // Check if promotional price is not greater than original price
    //         if ($req->promotion_price >= $req->unit_price) {
    //             return response()->json([
    //                 'message' => "The promotional price cannot be greater than the original price."
    //             ], 422);
    //         }
    //         // Create Product
    //         Products::create([
    //             'name' => $req->name,
    //             'id_type' => $req->id_type,
    //             'description' => $req->description,
    //             'unit_price' => $req->unit_price,
    //             'promotion_price' => $req->promotion_price,
    //             'image' => $imageName,
    //             'stock' => $req->stock,
    //             'unit' => $req->unit,
    //             'new' => $req->new
    //         ]);
     
    //         // Save Image in Storage folder
    //         $path = 'uploads/products/' . $imageName;
    //         Storage::disk('public')->put($path, file_get_contents($req->image));
     
    //         // Return Json Response
    //         return response()->json([
    //             'message' => "Product successfully created."
    //         ],200);
    //     } catch (\Exception $e) {
    //         // Return Json Response
    //         return response()->json([
    //             'message' => "Something went really wrong!"
    //         ],500);
    //     }
    // }

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
            // Check if promotional price is not greater than original price
            if ($req->promotion_price >= $req->unit_price) {
                return response()->json([
                    'message' => "The promotional price cannot be greater than the original price."
                ], 422);
            }
            $product->name = $req->name;
            $product->description = $req->description;

            $product->id_type = $req->id_type;
            $product->unit_price = $req->unit_price;
            $product->promotion_price = $req->promotion_price;
            $product->image = $req->image;
            $product->stock = $req->stock;
            $product->unit = $req->unit;
            $product->new = $req->new;
            
            // if ($req->hasFile('image')) {
            //     $image = $req->file('image');
            //     $image_base64 = base64_encode(file_get_contents($image));
            //     $product->image = $image_base64;
            // }
            // if($req->image) {
            //     // Public storage
            //     $storage = Storage::disk('public');
     
            //     // Old iamge delete
            //     if($storage->exists($product->image))
            //         $storage->delete($product->image);
     
            //     // Image name
            //     $imageName = Str::random(32).".".$req->image->getClientOriginalExtension();
            //     $product->image = 'uploads/products/'.$imageName; // specify the subfolder path
     
            //     // Image save in public folder
            //     $storage->put('uploads/products/'.$imageName, file_get_contents($req->image));// specify the subfolder path
            // }
     
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
     
        // // Public storage
        // $storage = Storage::disk('public');
     
        // // Iamge delete
        // if($storage->exists('uploads/products/'.$product->image)) // specify the subfolder path
        //     $storage->delete('uploads/products/'.$product->image);// specify the subfolder path
     
        // Delete Product
        $product->delete();
     
        // Return Json Response
        return response()->json([
            'message' => "Product successfully deleted."
        ],200);
    }
}
