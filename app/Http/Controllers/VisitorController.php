<?php

namespace App\Http\Controllers;

use App\Models\NewVisitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    // Mostrar todos los visitantes (índice)
    public function index(Request $request)
    {
        // Si hay una búsqueda por cédula
        if ($request->has('search') && $request->search != '') {
            $visitors = NewVisitor::where('visitor_identity_card', 'like', '%' . $request->search . '%')->get();
        } else {
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
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'visitor_photo' => 'required',
        ]);

        // Guardar la foto
        $imageData = $request->input('visitor_photo');
        $image = str_replace('data:image/jpeg;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $imageName = time() . '.jpg';
        $imagePath = public_path('visitor_photos/' . $imageName);
        file_put_contents($imagePath, base64_decode($image));

        // Crear el visitante
        NewVisitor::create([
            'visitor_name' => $request->visitor_name,
            'visitor_company' => $request->visitor_company,
            'visitor_identity_card' => $request->visitor_identity_card,
            'visitor_enter_time' => $request->visitor_enter_time,
            'visitor_out_time' => $request->visitor_out_time,  // Puede ser nulo
            'visitor_reason_to_meet' => $request->visitor_reason_to_meet,
            'visitor_photo' => 'visitor_photos/' . $imageName,
        ]);

        return redirect()->route('visitor.index')->with('success', 'Visitante agregado exitosamente');
    }

    // Mostrar el formulario de edición
    public function edit($id)
    {
        $visitor = NewVisitor::findOrFail($id);
        return view('edit_visitor', compact('visitor'));
    }

    // Actualizar visitante
    public function update(Request $request, $id)
    {
        // Validación de datos
        $request->validate([
            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'visitor_photo' => 'required',
        ]);

        $visitor = NewVisitor::findOrFail($id);

        // Si la foto fue cambiada
        if ($request->has('visitor_photo')) {
            $imageData = $request->input('visitor_photo');
            $image = str_replace('data:image/jpeg;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '.jpg';
            $imagePath = public_path('visitor_photos/' . $imageName);
            file_put_contents($imagePath, base64_decode($image));
            $visitor->visitor_photo = 'visitor_photos/' . $imageName;
        }

        // Actualizar datos
        $visitor->visitor_name = $request->visitor_name;
        $visitor->visitor_company = $request->visitor_company;
        $visitor->visitor_identity_card = $request->visitor_identity_card;
        $visitor->visitor_enter_time = $request->visitor_enter_time;
        $visitor->visitor_reason_to_meet = $request->visitor_reason_to_meet;
        $visitor->visitor_out_time = $request->visitor_out_time;

        $visitor->save();

        return redirect()->route('visitor.index')->with('success', 'Visitante actualizado exitosamente');
    }

    // Eliminar visitante
    public function delete($id)
    {
        $visitor = NewVisitor::findOrFail($id);

        // Eliminar la foto del visitante
        $imagePath = public_path($visitor->visitor_photo);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $visitor->delete();

        return redirect()->route('visitor.index')->with('success', 'Visitante eliminado exitosamente');
    }
}
