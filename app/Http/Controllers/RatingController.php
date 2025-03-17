<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'visitor_id' => 'required|exists:new_visitors,id', // Asegurarse de que el visitante existe
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Crear una nueva calificación
        $rating = new Rating();
        $rating->visitor_id = $request->visitor_id;
        $rating->rating = $request->rating;
        $rating->comment = $request->comment; // Si tienes un campo para comentarios
        $rating->save();

        // Respuesta en formato JSON
        return response()->json(['message' => 'Calificación registrada correctamente.']);
    }
}
