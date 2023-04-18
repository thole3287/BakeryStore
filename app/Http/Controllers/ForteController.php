<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForteRequest;
use App\Models\Forte;
use Illuminate\Http\Request;

class ForteController extends Controller
{
    public function index()
    {
        // All nasties
       $forte  = Forte::all();
        
        // Return Json Response
        return response()->json([
            'forte' =>$forte
        ],200);
    }

    public function get3InFor()
    {
       $forte  = Forte::orderBy('id', 'DESC')->take(3)->get();
         // Return Json Response
         return response()->json([
            'forte' =>$forte
        ],200);
    }



    public function show($id)
    {
       $forte = Forte::find($id);
        if(!$forte){
            return response()->json([
            'message'=>'Forte Not Found.'
            ],404);
        }

         // Return Json Response
       return response()->json([
        'forte' =>$forte
     ],200);
    }

    public function store(ForteRequest $request)
    {
        try {
            
            $imageData = file_get_contents($request->image);
            // Convert compressed data to base64
            $base64Data = base64_encode($imageData);
            Forte::create([
                'name' => $request->name,
                'description' =>  $request->description,
                // 'image' => $request->image
                'image' =>  $base64Data
            ]);
    
            return response()->json([
                'message' => "Forte successfully created."
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
        }
    }

    public function update(ForteRequest $request, $id)
    {
       try {
           $forte = Forte::find($id);
            if(!$forte){
                return response()->json([
                'message'=>'Forte Not Found.'
                ],404);
            }

           $forte->name = $request->name;
           $forte->description = $request->description;
           $forte->image = $request->image;
           $forte->save();

            return response()->json([
                'message' => "Forte successfully updated."
            ],200);
       } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
       }
        
    }

    public function destroy($id)
    {
       $forte = Forte::find($id);
        if(! $forte){
            return response()->json([
            'message'=>'Forte Not Found.'
            ],404);
        }

       $forte->delete();
        return response()->json([
            'message' => 'Forte successfully deleted.'
        ]);
    }
}
