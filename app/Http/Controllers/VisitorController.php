<?php

namespace App\Http\Controllers;

use App\Models\NewVisitor;
use App\Models\Department;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $query = NewVisitor::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('visitor_identity_card', 'like', '%' . $request->search . '%');
        }

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $query->whereBetween('visitor_enter_time', [$request->start_date, $request->end_date]);
        }

        $visitors = $query->orderBy('visitor_enter_time', 'desc')
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
        $request->validate([
            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'visitor_photo' => 'required',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        $identityCard = $request->visitor_identity_card;

        // Guardar foto del visitante
        $imageData = $request->input('visitor_photo');
        $image = str_replace('data:image/jpeg;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $imageName = $identityCard . '.jpg';
        $imagePath = public_path('storage/visitor_photos/' . $imageName);
        file_put_contents($imagePath, base64_decode($image));

        NewVisitor::create([
            'visitor_name' => $request->visitor_name,
            'visitor_company' => $request->visitor_company,
            'visitor_identity_card' => $identityCard,
            'visitor_enter_time' => $request->visitor_enter_time,
            'visitor_out_time' => null,
            'visitor_reason_to_meet' => $request->visitor_reason_to_meet,
            'visitor_photo' => 'visitor_photos/' . $imageName,
            'department_id' => $request->department_id,
            'visitor_card' => $identityCard,
        ]);

        return redirect()->route('visitor.index')->with('success', 'Visitante agregado exitosamente');
    }

    public function edit($id)
    {
        $visitor = NewVisitor::findOrFail($id);
        $departments = Department::all();
        return view('edit_visitor', compact('visitor', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'visitor_photo' => 'nullable',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        $visitor = NewVisitor::findOrFail($id);
        $identityCard = $request->visitor_identity_card;

        // Actualizar foto del visitante si hay una nueva
        if ($request->has('visitor_photo')) {
            $imageData = $request->input('visitor_photo');
            $image = str_replace('data:image/jpeg;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = $identityCard . '.jpg';
            $imagePath = public_path('storage/visitor_photos/' . $imageName);
            file_put_contents($imagePath, base64_decode($image));
            $visitor->visitor_photo = 'visitor_photos/' . $imageName;
        }

        $visitor->visitor_name = $request->visitor_name;
        $visitor->visitor_company = $request->visitor_company;
        $visitor->visitor_identity_card = $identityCard;
        $visitor->visitor_enter_time = $request->visitor_enter_time;
        $visitor->visitor_reason_to_meet = $request->visitor_reason_to_meet;
        $visitor->department_id = $request->department_id;
        $visitor->visitor_card = $identityCard;

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

    public function report(Request $request)
    {
        $query = NewVisitor::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('visitor_identity_card', 'like', '%' . $request->search . '%');
        }

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $query->whereBetween('visitor_enter_time', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $visitors = $query->orderBy('visitor_enter_time', 'desc')->get();
        $departments = Department::all();

        return view('visitor_report', compact('visitors', 'departments'));
    }
}
