<?php

namespace App\Http\Controllers;

use App\Models\NewVisitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    // Mostrar todos los visitantes (índice) con búsqueda por cédula
    public function index(Request $request)
    {
        $search = $request->input('search');  // Recibir el parámetro de búsqueda

        if ($search) {
            // Si hay búsqueda, obtener los visitantes que coincidan con la cédula
            $visitors = NewVisitor::where('visitor_identity_card', 'like', '%' . $search . '%')->get();
        } else {
            // Si no hay búsqueda, obtener todos los visitantes
            $visitors = NewVisitor::all();
        }

        return view('visitor', compact('visitors'));
    }

    // Mostrar el formulario de agregar visitante
    public function add()
    {
        return view('add_visitor');
    }

    // Validar y guardar visitante
    public function add_validation(Request $request)
    {
        // Validación
        $request->validate([
            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'visitor_photo' => 'required',
        ]);

        // Convertir la imagen base64 en una imagen real
        $imageData = $request->input('visitor_photo');
        $image = str_replace('data:image/jpeg;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $imageName = time() . '.jpg'; // Nombre único para la imagen
        $imagePath = public_path('visitor_photos/' . $imageName); // Ruta de almacenamiento

        // Guardar la imagen en el servidor
        file_put_contents($imagePath, base64_decode($image));

        // Crear el visitante
        NewVisitor::create([
            'visitor_name' => $request->visitor_name,
            'visitor_company' => $request->visitor_company,
            'visitor_identity_card' => $request->visitor_identity_card,
            'visitor_enter_time' => $request->visitor_enter_time,
            'visitor_out_time' => $request->visitor_out_time,  // Puede ser nulo
            'visitor_reason_to_meet' => $request->visitor_reason_to_meet,
            'visitor_photo' => 'visitor_photos/' . $imageName, // Guardamos la ruta de la foto
        ]);

        return redirect()->route('visitor.index')->with('success', 'Visitante agregado exitosamente');
    }

    // Mostrar el formulario de edición
    public function edit($id)
    {
        $visitor = NewVisitor::findOrFail($id);
        return view('edit_visitor', compact('visitor'));
    }

    // Validar y actualizar visitante
    public function update(Request $request, $id)
    {
        // Validación
        $request->validate([
            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'visitor_photo' => 'nullable',  // Foto es opcional en la edición
        ]);

        // Buscar el visitante
        $visitor = NewVisitor::findOrFail($id);

        // Si se capturó una nueva foto
        if ($request->has('visitor_photo')) {
            // Convertir la imagen base64 en una imagen real
            $imageData = $request->input('visitor_photo');
            $image = str_replace('data:image/jpeg;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '.jpg'; // Nombre único para la imagen
            $imagePath = public_path('visitor_photos/' . $imageName); // Ruta de almacenamiento

            // Guardar la imagen en el servidor
            file_put_contents($imagePath, base64_decode($image));

            // Actualizar la ruta de la foto
            $visitor->visitor_photo = 'visitor_photos/' . $imageName;
        }

        // Actualizar los datos del visitante
        $visitor->visitor_name = $request->visitor_name;
        $visitor->visitor_company = $request->visitor_company;
        $visitor->visitor_identity_card = $request->visitor_identity_card;
        $visitor->visitor_enter_time = $request->visitor_enter_time;
        $visitor->visitor_reason_to_meet = $request->visitor_reason_to_meet;

        // Guardar los cambios
        $visitor->save();

        return redirect()->route('visitor.index')->with('success', 'Visitante actualizado exitosamente');
    }

    // Eliminar visitante
    public function delete($id)
    {
        $visitor = NewVisitor::find($id);
        if ($visitor) {
            // Eliminar la imagen también si es necesario
            $imagePath = public_path($visitor->visitor_photo);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $visitor->delete();
            return redirect()->route('visitor.index')->with('success', 'Visitante eliminado exitosamente');
        }

        return redirect()->route('visitor.index')->with('error', 'Visitante no encontrado');
    }
}
