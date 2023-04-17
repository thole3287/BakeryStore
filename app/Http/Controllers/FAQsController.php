<?php

namespace App\Http\Controllers;

use App\Models\FAQS;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class FAQsController extends Controller
{
    public function index()
    {
        // All Product
        $faq  = FAQS::all();
        
        // Return Json Response
        return response()->json([
            'faq' =>$faq 
        ],200);
    }

    public function show($id)
    {
        $faq = FAQS::find($id);
        if(!$faq){
            return response()->json([
            'message'=>'FAQs Not Found.'
            ],404);
        }

         // Return Json Response
       return response()->json([
        'faq' => $faq
     ],200);
    }

    public function store(Request $request)
    {
        
        try {

            FAQS::Create([
                'name' => $request->name,
                'description' =>  $request->description
            ]);
    
            return response()->json([
                'message' => "FAQs successfully created."
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
            $faq = FAQS::find($id);
            if(!$faq){
                return response()->json([
                'message'=>'FAQs Not Found.'
                ],404);
            }

            $faq->name = $request->name;
            $faq->description = $request->description;
            $faq->save();

            return response()->json([
                'message' => "FAQs successfully updated."
            ],200);
       } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
            ],500);
       }
        
    }

    public function destroy($id)
    {
        $faq = FAQS::find($id);
        if(!$faq){
            return response()->json([
            'message'=>'FAQs Not Found.'
            ],404);
        }

        $faq->delete();
        return response()->json([
            'message' => 'FAQs successfully deleted.'
        ]);
    }
}
