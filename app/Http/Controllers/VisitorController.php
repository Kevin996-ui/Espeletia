<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('visitor');
    }

    public function create()
    {
        return view('add_visitor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'visitor_meet_person_name' => 'required|string|max:255',
            'visitor_department' => 'required|string|max:255',
            'visitor_status' => 'required|string',
            'visitor_enter_time' => 'required|date',
            'visitor_out_time' => 'nullable|date',
        ]);

        Visitor::create([
            'visitor_name' => $request->visitor_name,
            'visitor_meet_person_name' => $request->visitor_meet_person_name,
            'visitor_department' => $request->visitor_department,
            'visitor_status' => $request->visitor_status,
            'visitor_enter_time' => now(),
            'visitor_out_time' => $request->visitor_out_time,
            'visitor_enter_by' => auth()->id(),
        ]);

        return redirect()->route('visitor.index')->with('success', 'Visitante agregado correctamente.');
    }

    public function fetch_all(Request $request)
    {
        if ($request->ajax()) {
            $query = Visitor::join('users', 'users.id', '=', 'visitors.visitor_enter_by');

            if (Auth::user()->type == 'User') {
                $query->where('visitors.visitor_enter_by', '=', Auth::user()->id);
            }

            $data = $query->get([
                'visitors.visitor_name',
                'visitors.visitor_meet_person_name',
                'visitors.visitor_department',
                'visitors.visitor_enter_time',
                'visitors.visitor_out_time',
                'visitors.visitor_status',
                'users.name',
                'visitors.id'
            ]);

            return response()->json([
                'data' => $data
            ]);
        }
    }

    public function delete($id)
    {
        $visitor = Visitor::find($id);
        if (!$visitor) {
            return redirect()->route('visitor.index')->with('error', 'Visitante no encontrado.');
        }
        $visitor->delete();
    }
}
