<?php

namespace App\Http\Controllers;

use App\Models\KeyLog;

use App\Models\KeyType;

use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Mail;

use App\Mail\KeyLogNotification;

use App\Exports\KeyLogExport;

use Barryvdh\DomPDF\Facade\Pdf;

use Maatwebsite\Excel\Facades\Excel;

class KeyLogController extends Controller
{

    public function index()
    {

        $keyLogs = KeyLog::orderBy('created_at', 'desc')->paginate(5);

        return view('key_log', compact('keyLogs'));

    }

    public function ajaxSearch(Request $request)
    {

        $search = $request->input('search');

        $keyLogs = KeyLog::when($search, function ($query) use ($search) {

            $query->where('identity_card_taken', 'like', "%{$search}%");

        })->orderBy('key_taken_at', 'desc')->get();

        $tableHtml = view('partials.keylog_table', compact('keyLogs'))->render();

        return response()->json(['table_html' => $tableHtml]);

    }

    public function create()
    {

        $usedKeyCodes = KeyLog::whereNull('key_returned_at')->pluck('key_code')->toArray();

        $usedCodesArray = [];

        foreach ($usedKeyCodes as $entry) {

            foreach (explode(',', $entry) as $code) {

                $usedCodesArray[] = trim($code);

            }

        }

        $keyTypes = KeyType::whereNotIn('name', $usedCodesArray)->get(['name', 'area']);

        return view('add_key_log', compact('keyTypes'));

    }

    public function store(Request $request)
    {

        $request->validate([

            'name_taken' => 'required|string|max:255',

            'identity_card_taken' => 'required|string|max:20',

            'area' => 'required|string|max:255',

            'key_code' => 'required|array|min:1',

            'key_code.*' => 'string|max:255',

        ]);

        $keyCodesString = implode(', ', $request->key_code);

        foreach ($request->key_code as $code) {

            $keyInUse = KeyLog::where(function ($query) use ($code) {

                $query->whereNull('key_returned_at')

                    ->where('key_code', 'like', "%$code%");

            })->exists();

            if ($keyInUse) {

                return back()->withErrors(['key_code' => "La llave '$code' ya está en uso y no puede ser asignada."])->withInput();

            }

        }

        $taken_photo = 'N/A';

        if ($request->has('hasTools') && $request->filled('taken_photo')) {

            $taken_photo = $request->taken_photo;

        }

        $keyLog = KeyLog::create([

            'name_taken' => $request->name_taken,

            'identity_card_taken' => $request->identity_card_taken,

            'area' => $request->area,

            'key_code' => $keyCodesString,

            'key_taken_at' => now(),

            'name_returned' => '-',

            'identity_card_returned' => '-',

            'returned_photo' => '-',

            'key_returned_at' => null,

        ]);

        foreach ($request->key_code as $code) {

            $keyType = KeyType::where('name', $code)->first();

            if ($keyType && $keyType->email) {

                Mail::to($keyType->email)->send(new KeyLogNotification($keyLog, $keyType));

            }

        }

        return redirect()->route('keylog.index')->with('success', 'Registro de llave creado con éxito');

    }

    public function edit($id)
    {

        $keyLog = KeyLog::findOrFail($id);

        $usedKeyCodes = KeyLog::whereNull('key_returned_at')

            ->where('key_code', '!=', $keyLog->key_code)

            ->pluck('key_code')

            ->toArray();

        $usedCodesArray = [];

        foreach ($usedKeyCodes as $entry) {

            foreach (explode(',', $entry) as $code) {

                $usedCodesArray[] = trim($code);

            }

        }

        $keyTypes = KeyType::whereNotIn('name', $usedCodesArray)->get(['name', 'area']);

        return view('edit_key_log', compact('keyLog', 'keyTypes'));

    }

    public function update(Request $request, $id)
    {

        $request->validate([

            'name_taken' => 'required|string|max:255',

            'identity_card_taken' => 'required|string|max:20',

            'area' => 'required|string|max:255',

            'key_code' => 'required|array|min:1',

            'key_code.*' => 'string|max:255',

        ]);

        $keyCodesString = implode(', ', $request->key_code);

        $taken_photo = 'N/A';

        if ($request->has('hasTools') && $request->filled('taken_photo')) {

            $taken_photo = $request->taken_photo;

        }

        $keyLog = KeyLog::findOrFail($id);

        $keyLog->update([

            'name_taken' => $request->name_taken,

            'identity_card_taken' => $request->identity_card_taken,

            'area' => $request->area,

            'key_code' => $keyCodesString,

            'taken_photo' => $taken_photo,

        ]);

        return redirect()->route('keylog.index')->with('success', 'Log de llave actualizado con éxito');

    }

    public function destroy($id)
    {

        $keyLog = KeyLog::findOrFail($id);

        $keyLog->delete();

        return redirect()->route('keylog.index')->with('success', 'Log de llave eliminado con éxito');

    }

    public function fetchAll(Request $request)
    {

        if ($request->ajax()) {

            $keyLogs = KeyLog::orderBy('created_at', 'desc');

            return DataTables::of($keyLogs)

                ->addColumn('action', function ($keyLog) {

                    return '<a href="' . route('keylog.edit', $keyLog->id) . '" class="btn btn-warning btn-sm">Editar</a>
<form action="' . route('keylog.destroy', $keyLog->id) . '" method="POST" style="display:inline;">

                        ' . csrf_field() . method_field('DELETE') . '
<button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
</form>';

                })

                ->make(true);

        }

    }

    public function registerReturn($id)
    {

        $keyLog = KeyLog::findOrFail($id);

        $keyLog->update([

            'key_returned_at' => now(),

            'name_returned' => auth()->user()->name ?? 'Sistema',

            'identity_card_returned' => auth()->user()->identity_card ?? '0000000000',

            'returned_photo' => '-',

        ]);

        return redirect()->route('keylog.index')->with('success', 'Devolución registrada correctamente.');

    }

    public function showReportForm(Request $request)
    {

        $query = KeyLog::query();

        if ($request->has('search') && $request->search != '') {

            $query->where('identity_card_taken', 'like', "%{$request->search}%");

        }

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {

            if ($request->start_date == $request->end_date) {

                $query->whereDate('key_taken_at', $request->start_date);

            } else {

                $query->whereBetween('key_taken_at', [$request->start_date, $request->end_date]);

            }

        }

        if ($request->has('key_code') && $request->key_code != '') {

            $query->where('key_code', 'like', "%{$request->key_code}%");

        }

        if ($request->has('area') && $request->area != '') {

            $query->where('area', 'like', "%{$request->area}%");

        }

        if ($request->has('no_return') && $request->no_return == '1') {

            $query->whereNull('key_returned_at');

        }

        $keyLogs = $query->orderBy('created_at', 'desc')->get();

        $keyTypes = KeyType::all();

        return view('keylog_report', compact('keyLogs', 'keyTypes'));

    }

    public function exportReport(Request $request, $format)
    {

        $query = KeyLog::query();

        if ($request->has('search') && $request->search != '') {

            $query->where('identity_card_taken', 'like', "%{$request->search}%");

        }

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {

            if ($request->start_date == $request->end_date) {

                $query->whereDate('key_taken_at', $request->start_date);

            } else {

                $query->whereBetween('key_taken_at', [$request->start_date, $request->end_date]);

            }

        }

        if ($request->has('key_code') && $request->key_code != '') {

            $query->where('key_code', 'like', "%{$request->key_code}%");

        }

        if ($request->has('area') && $request->area != '') {

            $query->where('area', 'like', "%{$request->area}%");

        }

        if ($request->has('no_return') && $request->no_return == '1') {

            $query->whereNull('key_returned_at');

        }

        $keyLogs = $query->orderBy('created_at', 'desc')->get();

        if ($format === 'csv') {

            return Excel::download(new KeyLogExport($keyLogs), 'reporte_llaves_' . now()->format('Ymd_His') . '.csv');

        }

        if ($format === 'pdf') {

            $generatedBy = auth()->user()->name ?? 'Sistema';

            $pdf = Pdf::loadView('keylog_export', [

                'keyLogs' => $keyLogs,

                'generated_by' => $generatedBy,

            ])->setPaper('a4', 'landscape');

            return $pdf->download('reporte_llaves_' . now()->format('Ymd_His') . '.pdf');

        }

        return redirect()->route('keylog.report')->with('error', 'Formato no válido');

    }

}
