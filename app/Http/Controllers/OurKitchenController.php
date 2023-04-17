<?php

namespace App\Http\Controllers;

use App\Models\Our_Kitchen;
use Illuminate\Http\Request;

class OurKitchenController extends Controller
{
    public function index()
    {
        // All ourKitchen
        $ourKitchen  = Our_Kitchen::all();
        
        // Return Json Response
        return response()->json([
            'ourKitchen' => $ourKitchen
        ],200);
    }

    public function show($id)
    {
       $ourKitchen = Our_Kitchen::find($id);
        if(!$ourKitchen){
            return response()->json([
            'message'=>'Kitchen Not Found.'
            ],404);
        }

         // Return Json Response
       return response()->json([
        'ourKitchen' =>$ourKitchen
     ],200);
    }

    public function store(Request $request)
    {
        try {
            
            // $imageData = file_get_contents($request->image);
            // // Convert compressed data to base64
            // $base64Data = base64_encode($imageData);
            Our_Kitchen::create([
                'name' => $request->name,
                'description' =>  $request->description,
                'image' => $request->image
                // 'image' => $base64Data
            ]);
    
            return response()->json([
                'message' => "Kitchen successfully created."
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
        }
    }

    public function update(Request $request, $id)
    {
       try {
            $ourKitchen = Our_Kitchen::find($id);
            if(!$ourKitchen){
                return response()->json([
                'message'=>'Kitchen Not Found.'
                ],404);
            }

            $ourKitchen->name = $request->name;
            $ourKitchen->description = $request->description;
            $ourKitchen->image = $request->image;
            $ourKitchen->save();

            return response()->json([
                'message' => "Kitchen successfully updated."
            ],200);
       } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
       }
        
    }
    public function destroy($id)
    {
        $ourKitchen = Our_Kitchen::find($id);
        if(!$ourKitchen){
            return response()->json([
            'message'=>'Kitchen Not Found.'
            ],404);
        }

        $ourKitchen->delete();
        return response()->json([
            'message' => 'Kitchen successfully deleted.'
        ]);
    }
}
