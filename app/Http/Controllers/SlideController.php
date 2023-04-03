<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlideRequest;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function index()
    {
        //all slide
        $slide = Slide::all();

        // Return Json Response
        return response()->json([
            'slide' => $slide
        ],200);
    }

    public function store(SlideRequest $req)
    {
        try {

            // $imageName = Str::random(32).".".$req->image->getClientOriginalExtension();
            // Read image data
            $imageData = file_get_contents($req->image);

            // Convert compressed data to base64
            $base64Data = base64_encode($imageData);

            Slide::create([
                'name' => $req->name,
                'link' => $req->link,
                'image' => $base64Data
            ]);

            // // Save Image in Storage folder
            // $path = 'uploads/slides/' . $imageName;
            // Storage::disk('public')->put($path, file_get_contents($req->image));
            
            // Return Json Response
            return response()->json([
                'message' => "Slide successfully created."
            ],200);
        } catch (\Exception $e) {
             // Return Json Response
             return response()->json([
                'message' => "Something went really wrong!",
                'image' => $base64Data
            ],500);
        }
    }
    public function show($id)
    {
       // Slide Detail 
       $slide = Slide::find($id);
       if(!$slide){
         return response()->json([
            'message'=>'Slide Not Found.'
         ],404);
       }
     
       // Return Json Response
       return response()->json([
          'slide' => $slide
       ],200);
    }

    public function update(SlideRequest $req, $id)
    {
        try {
            // Find product
            $slide = Slide::find($id);
            if(!$slide){
              return response()->json([
                'message'=>'Slide Not Found.'
              ],404);
            }
     
            $slide->name = $req->name;

            if ($req->hasFile('image')) {
                $image = $req->file('image');
                $image_base64 = base64_encode(file_get_contents($image));
                $slide->image = $image_base64;
            }
            
            // if($req->image) {
            //     // Public storage
            //     $storage = Storage::disk('public');
     
            //     // Old iamge delete
            //     if($storage->exists($slide->image))
            //         $storage->delete($slide->image);
     
            //     // Image name
            //     $imageName = Str::random(32).".".$req->image->getClientOriginalExtension();
            //     $slide->image = 'uploads/slides/'.$imageName; // specify the subfolder path
     
            //     // Image save in public folder
            //     $storage->put('uploads/slides/'.$imageName, file_get_contents($req->image));// specify the subfolder path
            // }
     
            // Update slide
            $slide->save();
     
            // Return Json Response
            return response()->json([
                'message' => "Slide successfully updated."
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
        $slide = Slide::find($id);
        if(!$slide){
          return response()->json([
             'message'=>'Slide Not Found.'
          ],404);
        }
     
        // // Public storage
        // $storage = Storage::disk('public');
     
        // // Iamge delete
        // if($storage->exists('uploads/slides/'.$slide->image)) // specify the subfolder path
        //     $storage->delete('uploads/slides/'.$slide->image);// specify the subfolder path
     
        // Delete slide
        $slide->delete();
     
        // Return Json Response
        return response()->json([
            'message' => "Slide successfully deleted."
        ],200);
    }
}
