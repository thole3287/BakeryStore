<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoNastiesRequest;
use App\Models\NO_NASTIES;
use Illuminate\Http\Request;

class NoNastiesController extends Controller
{
    public function index()
    {
        // All nasties
        $noNasties  = NO_NASTIES::all();
        
        // Return Json Response
        return response()->json([
            'noNasties' => $noNasties
        ],200);
    }

    public function index3InFor()
    {
        $noNasties  = NO_NASTIES::orderBy('id', 'DESC')->take(3)->get();
         // Return Json Response
         return response()->json([
            'noNasties' => $noNasties
        ],200);
    }



    public function show($id)
    {
        $noNasties = NO_NASTIES::find($id);
        if(!$noNasties){
            return response()->json([
            'message'=>'No Nasties Not Found.'
            ],404);
        }

         // Return Json Response
       return response()->json([
        'noNasties' => $noNasties
     ],200);
    }

    public function store(NoNastiesRequest $request)
    {
        try {
            
            // $imageData = file_get_contents($request->image);
            // // Convert compressed data to base64
            // $base64Data = base64_encode($imageData);
            NO_NASTIES::create([
                'name' => $request->name,
                'description' =>  $request->description,
                'image' => $request->image
            ]);
    
            return response()->json([
                'message' => "No Nasties successfully created."
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
            $noNasties = NO_NASTIES::find($id);
            if(!$noNasties){
                return response()->json([
                'message'=>'No Nasties Not Found.'
                ],404);
            }

            $noNasties->name = $request->name;
            $noNasties->description = $request->description;
            $noNasties->image = $request->image;
            $noNasties->save();

            return response()->json([
                'message' => "No Nasties successfully updated."
            ],200);
       } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
       }
        
    }

    public function destroy($id)
    {
        $noNasties = NO_NASTIES::find($id);
        if(!$noNasties){
            return response()->json([
            'message'=>'No Nasties Not Found.'
            ],404);
        }

        $noNasties->delete();
        return response()->json([
            'message' => 'No Nasties successfully deleted.'
        ]);
    }
}
