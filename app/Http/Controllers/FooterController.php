<?php

namespace App\Http\Controllers;

use App\Models\Footer;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index()
    {
        // All 
        $footer = Footer::all();
        
        // Return Json Response
        return response()->json([
            'footer' =>$footer
        ],200);
    }

    public function show($id)
    {
        $footer= Footer::find($id);
        if(!$footer){
            return response()->json([
            'message'=>'Footer Not Found.'
            ],404);
        }

         // Return Json Response
       return response()->json([
        'footer' => $footer
     ],200);
    }

    public function store(Request $request)
    {
        
        try {

            Footer::Create([
               'title_1' => $request->title_1,
               'description_1' => $request->description_1,
               'title_2' => $request->title_2,
               'description_2' => $request->description_2,
               'title_branch' => $request->title_branch,
               'title_3' => $request->title_3,
               'description_3' => $request->description_3,
               'title_4' => $request->title_4,
               'description_4' => $request->description_4,
               'title_5' => $request->title_5,
               'description_5' => $request->description_5,
            ]);
    
            return response()->json([
                'message' => "Footer successfully created."
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
            $footer= Footer::find($id);
            if(!$footer){
                return response()->json([
                'message'=>'Footer Not Found.'
                ],404);
            }

            $footer->title_1 = $request->title_1;
            $footer->description_1 = $request->description_1;
            $footer->title_2 = $request->title_2;
            $footer->description_2 = $request->description_2;
            $footer->title_branch = $request->title_branch;
            $footer->title_3 = $request->title_3;
            $footer->description_3 = $request->description_3;
            $footer->title_4 = $request->title_4;
            $footer->description_4 = $request->description_4;
            $footer->title_5 = $request->title_5;
            $footer->description_5 = $request->description_5;
            $footer->save();

            return response()->json([
                'message' => "Footer successfully updated."
            ],200);
       } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
       }
        
    }

    public function destroy($id)
    {
        $footer= Footer::find($id);
        if(!$footer){
            return response()->json([
            'message'=>'Footer Not Found.'
            ],404);
        }

        $footer->delete();
        return response()->json([
            'message' => 'Footer successfully deleted.'
        ]);
    }
}
