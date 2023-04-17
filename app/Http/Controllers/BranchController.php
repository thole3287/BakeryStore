<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        // All branch
        $branch  = Branch::all();
        
        // Return Json Response
        return response()->json([
            'branch' =>$branch
        ],200);
    }

    public function show($id)
    {
       $branch = Branch::find($id);
        if(!$branch){
            return response()->json([
            'message'=>'Branch Not Found.'
            ],404);
        }

         // Return Json Response
       return response()->json([
        'branch' =>$branch
     ],200);
    }

    public function store(BranchRequest $request)
    {
        
        try {
            
            // $imageData = file_get_contents($request->image);
            // // Convert compressed data to base64
            // $base64Data = base64_encode($imageData);
            Branch::create([
                'name' => $request->name,
                'address' => $request->address,
                'time_open' =>  $request->time_open,
                'image' => $request->image
            ]);
    
            return response()->json([
                'message' => "Branch successfully created."
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
        }
    }

    public function update(BranchRequest $request, $id)
    {
       try {
            $branch = Branch::find($id);
            if(!$branch){
                return response()->json([
                'message'=>'Branch Not Found.'
                ],404);
            }

            $branch->name = $request->name;
            $branch->address = $request->address;
            $branch->time_open = $request->time_open;
            $branch->image = $request->image;
            // if ($request->hasFile('image')) {
            //     $image = $request->file('image');
            //     $image_base64 = base64_encode(file_get_contents($image));
            //     $branch->image = $image_base64;
            // }

            $branch->save();

            return response()->json([
                'message' => "Branch successfully updated."
            ],200);
       } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
       }
        
    }
    public function destroy($id)
    {
        $branch = Branch::find($id);
        if(!$branch){
            return response()->json([
            'message'=>'Branch Not Found.'
            ],404);
        }

        $branch->delete();
        return response()->json([
            'message' => 'Branch successfully deleted.'
        ]);
    }

}
