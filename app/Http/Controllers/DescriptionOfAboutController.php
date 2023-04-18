<?php

namespace App\Http\Controllers;

use App\Models\About_Baker;
use Illuminate\Http\Request;

class DescriptionOfAboutController extends Controller
{
    public function index()
    {
        // All nasties
       $aboutBaker = About_Baker::all();
        
        // Return Json Response
        return response()->json([
            'aboutBaker' =>$aboutBaker
        ],200);
    }

    public function show($id)
    {
       $aboutBaker = About_Baker::find($id);
        if(!$aboutBaker){
            return response()->json([
            'message'=>'About Baker Not Found.'
            ],404);
        }

         // Return Json Response
       return response()->json([
        'aboutBaker' => $aboutBaker
     ],200);
    }

    public function store(Request $request)
    {
        try {
           About_Baker::create([
                'title' => $request->title,
                'description_1' =>  $request->description_1,
                'description_2' =>  $request->description_2
            ]);
    
            return response()->json([
                'message' => "About Baker successfully created."
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
           $aboutBaker = About_Baker::find($id);
            if(!$aboutBaker){
                return response()->json([
                'message'=>'aboutBakerNot Found.'
                ],404);
            }

            $aboutBaker->title = $request->title;
            $aboutBaker->description_1 = $request->description_1;
            $aboutBaker->description_2 = $request->description_2;
            $aboutBaker->save();

            return response()->json([
                'message' => "About Baker successfully updated."
            ],200);
       } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
       }
        
    }

    public function destroy($id)
    {
       $aboutBaker = About_Baker::find($id);
        if(!$aboutBaker){
            return response()->json([
            'message'=>'About Baker Not Found.'
            ],404);
        }
        $aboutBaker->delete();
        return response()->json([
            'message' => 'About Baker successfully deleted.'
        ]);
    }
}
