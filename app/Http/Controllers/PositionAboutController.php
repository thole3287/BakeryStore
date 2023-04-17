<?php

namespace App\Http\Controllers;

use App\Models\PositionAbout;
use Illuminate\Http\Request;

class PositionAboutController extends Controller
{
    public function index()
    {
        // All $positions
        $position  = PositionAbout::all();
        
        // Return Json Response
        return response()->json([
            'position' => $position
        ],200);
    }

    public function show($id)
    {
         $position = PositionAbout::find($id);
        if(! $position){
            return response()->json([
            'message'=>'Position Not Found.'
            ],404);
        }

         // Return Json Response
       return response()->json([
        'position' =>  $position
     ],200);
    }

    public function store(Request $request)
    {
        try {
            
            // $imageData = file_get_contents($request->image);
            // // Convert compressed data to base64
            // $base64Data = base64_encode($imageData);
            PositionAbout::create([
                'name' => $request->name,
                'description' =>  $request->description,
                'image' => $request->image
                // 'image' =>  $base64Data

            ]);
    
            return response()->json([
                'message' => " Position successfully created."
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
            $position = PositionAbout::find($id);
            if(!$position){
                return response()->json([
                'message'=>'Position Not Found.'
                ],404);
            }

            $position->name = $request->name;
            $position->description = $request->description;
            $position->image = $request->image;
            $position->save();

            return response()->json([
                'message' => "Position successfully updated."
            ],200);
       } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
       }
        
    }

    public function destroy($id)
    {
        $position = PositionAbout::find($id);
        if(! $position){
            return response()->json([
            'message'=>'Position Not Found.'
            ],404);
        }

        $position->delete();
        return response()->json([
            'message' => 'Position successfully deleted.'
        ]);
    }
}
