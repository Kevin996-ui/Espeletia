<?php

namespace App\Http\Controllers;

use App\Models\NewVisitor;
use App\Models\Department;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade as PDF;

class VisitorController extends Controller
{
    // Agregar validación para usuario autenticado
    public function index(Request $request)
    {
        $query = NewVisitor::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('visitor_identity_card', 'like', '%' . $request->search . '%');
        }

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $query->whereBetween('visitor_enter_time', [$request->start_date, $request->end_date]);
        }

        $visitors = $query->with('department') // Cargar relación de department
                          ->orderBy('visitor_enter_time', 'desc')
                          ->paginate(5);

        return view('visitor', compact('visitors'));
    }

    public function add()
    {
        $departments = Department::all();
        return view('add_visitor', compact('departments'));
    }

    public function store(Request $request)
    {
        // Validación
        $rules = [
            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'visitor_photo' => 'required',
            'department_id' => 'nullable|exists:departments,id',
            'visitor_card' => 'nullable', // Campo no es obligatorio en este caso
        ];

        $request->validate($rules);

        // Manejar foto del visitante
        $identityCard = $request->visitor_identity_card;
        $visitor_card = $request->visitor_card;
        $photoPath = $this->handleVisitorPhoto($request->input('visitor_photo'), $identityCard);

        // Crear el visitante
        NewVisitor::create([
            'visitor_name' => $request->visitor_name,
            'visitor_company' => $request->visitor_company,
            'visitor_identity_card' => $identityCard,
            'visitor_enter_time' => $request->visitor_enter_time,
            'visitor_out_time' => null,
            'visitor_reason_to_meet' => $request->visitor_reason_to_meet,
            'visitor_photo' => $photoPath,
            'department_id' => $request->department_id,
            'visitor_card' => $visitor_card,  // Solo si es proveedor
        ]);

        return redirect()->route('visitor.index')->with('success', 'Visitante agregado exitosamente');
    }

    // Manejo de foto del visitante
    private function handleVisitorPhoto($imageData, $identityCard)
    {
        $image = str_replace('data:image/jpeg;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $imageName = $identityCard . '.jpg'; // Nombre de la foto basado en identity_card
        $imagePath = public_path('storage/visitor_photos/' . $imageName);
        file_put_contents($imagePath, base64_decode($image));

        return 'visitor_photos/' . $imageName;
    }

    public function edit($id)
    {
        $visitor = NewVisitor::findOrFail($id);
        $departments = Department::all();
        return view('edit_visitor', compact('visitor', 'departments'));
    }

    public function update(Request $request, $id)
    {
        // Validación de los campos
        $rules = [
            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'visitor_photo' => 'nullable',
            'department_id' => 'nullable|exists:departments,id',
            'visitor_card' => 'nullable', // Campo no es obligatorio en este caso
        ];

        $request->validate($rules);

        $visitor = NewVisitor::findOrFail($id);
        $identityCard = $request->visitor_identity_card;
        $visitor_card = $request->visitor_card;

        // Actualizar foto si es necesario
        if ($request->has('visitor_photo')) {
            $photoPath = $this->handleVisitorPhoto($request->input('visitor_photo'), $identityCard);
            $visitor->visitor_photo = $photoPath;
        }

        // Actualizar otros campos
        $visitor->visitor_name = $request->visitor_name;
        $visitor->visitor_company = $request->visitor_company;
        $visitor->visitor_identity_card = $identityCard;
        $visitor->visitor_enter_time = $request->visitor_enter_time;
        $visitor->visitor_reason_to_meet = $request->visitor_reason_to_meet;
        $visitor->department_id = $request->department_id;
        $visitor->visitor_card = $visitor_card; // Solo si es proveedor

        // Guardar los cambios en la base de datos
        $visitor->save();

        return redirect()->route('visitor.index')->with('success', 'Visitante actualizado exitosamente');
    }

    public function delete($id)
    {
        $visitor = NewVisitor::findOrFail($id);

        // Eliminar foto del visitante
        $imagePath = public_path($visitor->visitor_photo);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $visitor->delete();

        return redirect()->route('visitor.index')->with('success', 'Visitante eliminado exitosamente');
    }

    public function registerExit($id)
    {
        $visitor = NewVisitor::findOrFail($id);
        $visitor->visitor_out_time = now();
        $visitor->save();

        return redirect()->route('visitor.index')->with('success', 'Hora de salida registrada.');
    }

    public function showReportForm(Request $request)
    {
        // Obtener todos los departamentos para mostrarlos en el formulario
        $departments = Department::all();

        // Realizar la consulta de los visitantes con los filtros aplicados
        $query = NewVisitor::query();

        // Filtro por número de cédula
        if ($request->has('search') && $request->search != '') {
            $query->where('visitor_identity_card', 'like', '%' . $request->search . '%');
        }

        // Filtro por fechas (mismo día o rango de fechas)
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            // Si las fechas son iguales, consideramos que se busca por un solo día
            if ($request->start_date == $request->end_date) {
                $query->whereDate('visitor_enter_time', $request->start_date);  // Solo ese día
            } else {
                // Rango de fechas
                $query->whereBetween('visitor_enter_time', [$request->start_date, $request->end_date]);
            }
        }

        // Filtro por departamento
        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        // Filtro por visitantes sin salida registrada
        if ($request->has('no_exit') && $request->no_exit == '1') {
            $query->whereNull('visitor_out_time');
        }

        // Filtro por Tarjeta de Visitante
        if ($request->has('visitor_card') && $request->visitor_card != '') {
            $query->where('visitor_card', 'like', '%' . $request->visitor_card . '%');
        }

        // Obtener los visitantes filtrados
        $visitors = $query->with('department')->get(); // Puedes cambiar get() por paginate() si prefieres paginación

        // Pasar los departamentos y los visitantes a la vista
        return view('visitor_report', compact('departments', 'visitors'));
    }

}
