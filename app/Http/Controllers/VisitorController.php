<?php

namespace App\Http\Controllers;

use App\Models\NewVisitor;
use App\Models\Department;
use App\Models\Card;
use Illuminate\Http\Request;
use App\Mail\VisitorNotification;
use Illuminate\Support\Facades\Mail;
use App\Exports\VisitorExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

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

        $visitors = $query->with('department', 'card')->orderBy('visitor_enter_time', 'desc')->paginate(5);

        return view('visitor', compact('visitors'));
    }

    public function ajaxSearch(Request $request)

    {

        $search = $request->input('search');
        $visitors = NewVisitor::with('department', 'card')

            ->when($search, function ($q) use ($search) {

                return $q->where('visitor_identity_card', 'like', "%{$search}%");
            })

            ->orderBy('visitor_enter_time', 'desc')
            ->get();

        $table_html = view('partials.visitor_table', compact('visitors'))->render();

        return response()->json(['table_html' => $table_html]);
    }

    public function add()

    {
        $departments = Department::all();
        $usedCardIds = NewVisitor::whereNull('visitor_out_time')->pluck('card_id')->filter()->toArray();
        $cards = Card::whereNotIn('id', $usedCardIds)->get();
        return view('add_visitor', compact('departments', 'cards'));
    }

    public function store(Request $request)

    {
        $request->validate([

            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'department_id' => 'nullable|exists:departments,id',
            'visitor_card' => 'nullable|string|max:255',
            'card_id' => 'nullable|exists:cards,id',
            'visitor_photo' => 'nullable|string|max:255',

        ]);

        $identityCard = $request->visitor_identity_card;
        $visitor = NewVisitor::create([

            'visitor_name' => $request->visitor_name,
            'visitor_company' => $request->visitor_company,
            'visitor_identity_card' => $identityCard,
            'visitor_enter_time' => $request->visitor_enter_time,
            'visitor_out_time' => null,
            'visitor_reason_to_meet' => $request->visitor_reason_to_meet,
            'department_id' => $request->department_id,
            'visitor_card' => $request->visitor_card,
            'card_id' => $request->card_id,
            'visitor_photo' => $request->input('visitor_photo') ?? null,

        ]);

        $department = Department::find($request->department_id);
        Mail::to($department->contact_emails)->send(new VisitorNotification($visitor, $department));
        return redirect()->route('visitor.index')->with('success', 'Visitante agregado exitosamente');
    }

    public function edit($id)

    {

        $visitor = NewVisitor::findOrFail($id);
        $departments = Department::all();
        $usedCardIds = NewVisitor::where('id', '!=', $visitor->id)->whereNull('visitor_out_time')->pluck('card_id')->filter()->toArray();
        $cards = Card::where(function ($query) use ($usedCardIds, $visitor) {

            $query->whereNotIn('id', $usedCardIds)->orWhere('id', $visitor->card_id);
        })->get();

        return view('edit_visitor', compact('visitor', 'departments', 'cards'));
    }

    public function update(Request $request, $id)

    {

        $request->validate([

            'visitor_name' => 'required',
            'visitor_company' => 'required',
            'visitor_identity_card' => 'required',
            'visitor_enter_time' => 'required|date',
            'visitor_reason_to_meet' => 'required',
            'visitor_photo' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'visitor_card' => 'nullable|string|max:255',
            'card_id' => 'nullable|exists:cards,id',

        ]);

        $visitor = NewVisitor::findOrFail($id);
        $visitor->visitor_name = $request->visitor_name;
        $visitor->visitor_company = $request->visitor_company;
        $visitor->visitor_identity_card = $request->visitor_identity_card;
        $visitor->visitor_enter_time = $request->visitor_enter_time;
        $visitor->visitor_reason_to_meet = $request->visitor_reason_to_meet;
        $visitor->department_id = $request->department_id;
        $visitor->visitor_card = $request->visitor_card;
        $visitor->card_id = $request->card_id;
        $visitor->visitor_photo = $request->input('visitor_photo') ?? null;
        $visitor->save();
        $department = Department::find($visitor->department_id);

        Mail::to($department->contact_emails)->send(new VisitorNotification($visitor, $department));

        return redirect()->route('visitor.index')->with('success', 'Visitante actualizado exitosamente')->withInput();
    }

    public function delete($id)

    {
        $visitor = NewVisitor::findOrFail($id);
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

        $departments = Department::all();
        $query = NewVisitor::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('visitor_identity_card', 'like', '%' . $request->search . '%');
        }

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {

            if ($request->start_date == $request->end_date) {

                $query->whereDate('visitor_enter_time', $request->start_date);
            } else {

                $query->whereBetween('visitor_enter_time', [$request->start_date, $request->end_date]);
            }
        }

        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('no_exit') && $request->no_exit == '1') {
            $query->whereNull('visitor_out_time');
        }

        if ($request->has('visitor_card') && $request->visitor_card != '') {
            $query->where('visitor_card', 'like', '%' . $request->visitor_card . '%');
        }

        $visitors = $query->with('department', 'card')->get();
        return view('visitor_report', compact('departments', 'visitors'));
    }

    public function exportReport(Request $request, $format)

    {
        $query = NewVisitor::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('visitor_identity_card', 'like', '%' . $request->search . '%');
        }

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            if ($request->start_date == $request->end_date) {
                $query->whereDate('visitor_enter_time', $request->start_date);
            } else {
                $query->whereBetween('visitor_enter_time', [$request->start_date, $request->end_date]);
            }
        }

        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('no_exit') && $request->no_exit == '1') {
            $query->whereNull('visitor_out_time');
        }

        if ($request->has('visitor_card') && $request->visitor_card != '') {
            $query->where('visitor_card', 'like', '%' . $request->visitor_card . '%');
        }

        $visitors = $query->with('department', 'card')->get();

        if ($format === 'csv') {
            $filename = 'reporte_visitantes_' . now()->format('Ymd_His') . '.csv';
            return Excel::download(new VisitorExport($visitors), $filename);
        }

        if ($format === 'pdf') {

            $generated_by = auth()->check() ? (auth()->user()->name ?? auth()->user()->email) : 'Usuario no autenticado';
            $pdf = Pdf::loadView('exports.visitor_export', [

                'visitors' => $visitors,
                'generated_by' => $generated_by,

            ])->setPaper('a4', 'landscape');

            return $pdf->download('reporte_visitantes_' . now()->format('Ymd_His') . '.pdf');
        }

        return redirect()->route('visitor.report')->with('error', 'Formato no válido');
    }
}
