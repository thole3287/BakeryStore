<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DescriptionOfAboutController extends Controller
{
    // Get all descriptions
    public function index()
    {
        $title = 'ABOUT BAKES';
        $descriptions = [
            1 => ["Bakes believes pastry should be like love: exciting, thoughtful, and honest."],
            2 => ["Since 2015, we have been constantly developing new pastry flavors, designs, and experiences to keep guests guessing. Our creativity doesn't stop at pastries. We find thoughtful ways to design packaging so you can gift it, or use it as a plate, and other ways to make your life easier. All the while using only natural ingredients and absolutely no preservatives."],
            3 => ["Bakes is founded in 2015 by Laure Chevallier, a French hospitality entrepreneur and Tuan Le, a Vietnamese-American Creative Director."],
            4 => ["Our Executive Pastry Chef is Brian Gartner, from Four Seasons and Ritz-Calrton. The baking team comes from IECD, a French baking academy. Our operations team are women-led and LGBTQI+ friendly"]
        ];
        return response()->json(['description' => $descriptions, 'title' => $title]);
    }

    // Get a single description by key
    public function show($id)
    {
        $title = 'ABOUT BAKES';
        $description = [
            1 => ["Bakes believes pastry should be like love: exciting, thoughtful, and honest."],
            2 => ["Since 2015, we have been constantly developing new pastry flavors, designs, and experiences to keep guests guessing. Our creativity doesn't stop at pastries. We find thoughtful ways to design packaging so you can gift it, or use it as a plate, and other ways to make your life easier. All the while using only natural ingredients and absolutely no preservatives."],
            3 => ["Bakes is founded in 2015 by Laure Chevallier, a French hospitality entrepreneur and Tuan Le, a Vietnamese-American Creative Director."],
            4 => ["Our Executive Pastry Chef is Brian Gartner, from Four Seasons and Ritz-Calrton. The baking team comes from IECD, a French baking academy. Our operations team are women-led and LGBTQI+ friendly"]
        ];
        if (array_key_exists($id, $description)) {
            return response()->json([$id => $description[$id], 'title' =>$title]);
        } else {
            return response()->json(["message" => "Description not found"], 404);
        }
    }

    // Create a new description
    public function store(Request $request)
    {
        $description = $request->all();
        // Add the new description to the array
        $description[count($description) + 1] = [$description['value']];
        return response()->json([$description]);
    }

    // Update an existing description
    public function update(Request $request, $id)
    {
        $description = [
            1 => ["Bakes believes pastry should be like love: exciting, thoughtful, and honest."],
            2 => ["Since 2015, we have been constantly developing new pastry flavors, designs, and experiences to keep guests guessing. Our creativity doesn't stop at pastries. We find thoughtful ways to design packaging so you can gift it, or use it as a plate, and other ways to make your life easier. All the while using onlynatural ingredients and absolutely no preservatives."],
            3 => ["Bakes is founded in 2015 by Laure Chevallier, a French hospitality entrepreneur and Tuan Le, a Vietnamese-American Creative Director."],
            4 => ["Our Executive Pastry Chef is Brian Gartner, from Four Seasons and Ritz-Calrton. The baking team comes from IECD, a French baking academy. Our operations team are women-led and LGBTQI+ friendly"]
        ];
        if (array_key_exists($id, $description)) {
            $description[$id] = [$request->input('value')];
            return response()->json([$id => $description[$id]]);
        } else {
            return response()->json(["message" => "Description not found"], 404);
        }
    }

    public function destroy($id)
    {
        $description = [        
            1 => ["Bakes believes pastry should be like love: exciting, thoughtful, and honest."],
            2 => ["Since 2015, we have been constantly developing new pastry flavors, designs, and experiences to keep guests guessing. Our creativity doesn't stop at pastries. We find thoughtful ways to design packaging so you can gift it, or use it as a plate, and other ways to make your life easier. All the while using only natural ingredients and absolutely no preservatives."],
            3 => ["Bakes is founded in 2015 by Laure Chevallier, a French hospitality entrepreneur and Tuan Le, a Vietnamese-American Creative Director."],
            4 => ["Our Executive Pastry Chef is Brian Gartner, from Four Seasons and Ritz-Calrton. The baking team comes from IECD, a French baking academy. Our operations team are women-led and LGBTQI+ friendly"]
        ];
        if (array_key_exists($id, $description)) {
            unset($description[$id]);
            return response()->json(["message" => "Description deleted successfully"], 200);
        } else {
            return response()->json(["message" => "Description not found"], 404);
        }
    }
}
