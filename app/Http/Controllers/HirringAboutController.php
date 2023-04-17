<?php

namespace App\Http\Controllers;

use App\Models\HirringAbout;
use Illuminate\Http\Request;

class HirringAboutController extends Controller
{
    public function index()
    {
        // All $positions
        $hirring  = HirringAbout::all();
        
        // Return Json Response
        return response()->json([
            'hirring' => $hirring
        ],200);
    }

    public function show($id)
    {
         $hirring = HirringAbout::find($id);
        if(! $hirring){
            return response()->json([
            'message'=>'Hirring Not Found.'
            ],404);
        }

         // Return Json Response
       return response()->json([
        'hirring' =>  $hirring
     ],200);
    }

    public function store(Request $request)
    {
        try {
            
            $imageData = file_get_contents($request->image);
            // Convert compressed data to base64
            $base64Data = base64_encode($imageData);
            HirringAbout::create([
                'title' => $request->title,
                'description' =>  $request->description,
                // 'image' => $request->image
                'image' =>  $base64Data

            ]);
    
            return response()->json([
                'message' => " Hirring successfully created."
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
            $hirring = HirringAbout::find($id);
            if(!$hirring){
                return response()->json([
                'message'=>'Hirring Not Found.'
                ],404);
            }

            $hirring->title = $request->title;
            $hirring->description = $request->description;
            $hirring->image = $request->image;
            $hirring->save();

            return response()->json([
                'message' => "Hirring successfully updated."
            ],200);
       } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
       }
        
    }

    public function destroy($id)
    {
        $hirring = HirringAbout::find($id);
        if(! $hirring){
            return response()->json([
            'message'=>'Hirring Not Found.'
            ],404);
        }

        $hirring->delete();
        return response()->json([
            'message' => 'Hirring successfully deleted.'
        ]);
    }
}
