<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|exists:new_visitors,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $rating = new Rating();
        $rating->visitor_id = $request->visitor_id;
        $rating->rating = $request->rating;
        $rating->comment = $request->comment;
        $rating->save();

        return response()->json(['message' => 'Calificación registrada correctamente.']);
    }
}
