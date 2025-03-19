<?php

namespace App\Http\Controllers;

use App\Models\NewVisitor;
use App\Models\Department;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    // Mostrar todos los visitantes (índice)
    public function index(Request $request)
    {
        // Crear la consulta base
        $query = NewVisitor::query();

        // Filtrar por cédula si se proporciona
        if ($request->has('search') && $request->search != '') {
            $query->where('visitor_identity_card', 'like', '%' . $request->search . '%');
        }

        // Filtrar por fechas si están presentes
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $query->whereBetween('visitor_enter_time', [$request->start_date, $request->end_date]);
        }

        // Obtener los visitantes filtrados y paginados
        $visitors = $query->orderBy('visitor_enter_time', 'desc') // Orden descendente por la hora de entrada
                          ->paginate(5); // Paginación con 5 visitantes por página

        // Pasar los datos a la vista
        return view('visitor', compact('visitors'));
    }

    // Mostrar formulario para agregar un visitante
    public function add()
    {
        $departments = Department::all();
        return view('add_visitor', compact('departments'));
    }

    // Almacenar un nuevo visitante
    public function store(Request $request)
    {
        $request->validate([
            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'visitor_photo' => 'required',
            'department_id' => 'nullable|exists:departments,id',  // Validación para departamento
        ]);

        // Obtener la cédula del visitante para usarla como nombre de archivo
        $identityCard = $request->visitor_identity_card;

        // Obtener los datos de la imagen capturada
        $imageData = $request->input('visitor_photo');
        $image = str_replace('data:image/jpeg;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);

        // Usar la cédula como nombre del archivo de la imagen
        $imageName = $identityCard . '.jpg';

        // Definir la ruta para guardar la imagen en el directorio 'visitor_photos'
        $imagePath = public_path('storage/visitor_photos/' . $imageName);

        // Guardar la imagen en el directorio especificado
        file_put_contents($imagePath, base64_decode($image));

        // Crear el visitante en la base de datos
        NewVisitor::create([
            'visitor_name' => $request->visitor_name,
            'visitor_company' => $request->visitor_company,
            'visitor_identity_card' => $identityCard,
            'visitor_enter_time' => $request->visitor_enter_time,
            'visitor_out_time' => null, // No tiene hora de salida al inicio
            'visitor_reason_to_meet' => $request->visitor_reason_to_meet,
            'visitor_photo' => 'visitor_photos/' . $imageName, // Guardar la ruta en la BD
            'department_id' => $request->department_id,  // Guardar el ID del departamento
        ]);

        return redirect()->route('visitor.index')->with('success', 'Visitante agregado exitosamente');
    }

    // Mostrar formulario para editar un visitante
    public function edit($id)
    {
        $visitor = NewVisitor::findOrFail($id);
        $departments = Department::all();  // Obtener todos los departamentos
        return view('edit_visitor', compact('visitor', 'departments'));  // Pasar los departamentos a la vista
    }

    // Actualizar un visitante existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'visitor_photo' => 'nullable',
            'department_id' => 'nullable|exists:departments,id',  // Validación para el departamento
        ]);

        $visitor = NewVisitor::findOrFail($id);

        // Si la cédula ha cambiado o si se quiere actualizar la foto
        $identityCard = $request->visitor_identity_card;

        if ($request->has('visitor_photo')) {
            // Obtener los datos de la imagen capturada
            $imageData = $request->input('visitor_photo');
            $image = str_replace('data:image/jpeg;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);

            // Usar la cédula como nombre del archivo de la imagen
            $imageName = $identityCard . '.jpg';

            // Definir la ruta para guardar la imagen
            $imagePath = public_path('storage/visitor_photos/' . $imageName);

            // Guardar la imagen en el directorio especificado
            file_put_contents($imagePath, base64_decode($image));

            // Actualizar el nombre de la imagen en la base de datos
            $visitor->visitor_photo = 'visitor_photos/' . $imageName;
        }

        // Actualizar otros campos del visitante
        $visitor->visitor_name = $request->visitor_name;
        $visitor->visitor_company = $request->visitor_company;
        $visitor->visitor_identity_card = $identityCard;
        $visitor->visitor_enter_time = $request->visitor_enter_time;
        $visitor->visitor_reason_to_meet = $request->visitor_reason_to_meet;
        $visitor->department_id = $request->department_id;  // Actualizar el campo department_id

        // Guardar los cambios
        $visitor->save();

        return redirect()->route('visitor.index')->with('success', 'Visitante actualizado exitosamente');
    }

    // Eliminar un visitante
    public function delete($id)
    {
        $visitor = NewVisitor::findOrFail($id);

        // Borrar la foto del visitante si existe
        $imagePath = public_path($visitor->visitor_photo);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Eliminar el visitante
        $visitor->delete();

        return redirect()->route('visitor.index')->with('success', 'Visitante eliminado exitosamente');
    }

    // Registrar la salida del visitante
    public function registerExit($id)
    {
        $visitor = NewVisitor::findOrFail($id);
        // Registrar la hora actual como hora de salida
        $visitor->visitor_out_time = now();

        $visitor->save();

        return redirect()->route('visitor.index')->with('success', 'Hora de salida registrada.');
    }

    // Reporte de visitantes
    public function report(Request $request)
    {
        // Crear la consulta base
        $query = NewVisitor::query();

        // Filtrar por cédula si se proporciona
        if ($request->has('search') && $request->search != '') {
            $query->where('visitor_identity_card', 'like', '%' . $request->search . '%');
        }

        // Filtrar por fechas si están presentes
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $query->whereBetween('visitor_enter_time', [
                $request->start_date . ' 00:00:00', // Comienza desde el inicio del día
                $request->end_date . ' 23:59:59'    // Termina hasta el final del día
            ]);
        }

        // Filtro por departamento si está presente
        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        // Filtro por visitantes sin salida registrada
        if ($request->has('no_exit') && $request->no_exit == '1') {
            $query->whereNull('visitor_out_time');
        }

        // Obtener todos los visitantes sin paginación
        $visitors = $query->orderBy('visitor_enter_time', 'desc') // Orden descendente por la hora de entrada
                          ->get(); // Obtener todos los visitantes

        // Obtener todos los departamentos
        $departments = Department::all();

        // Pasar los datos a la vista
        return view('visitor_report', compact('visitors', 'departments'));
    }
}
