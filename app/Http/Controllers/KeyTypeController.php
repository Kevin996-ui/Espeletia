<?php

namespace App\Http\Controllers;

use App\Models\KeyType;

use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

use Carbon\Carbon;

class KeyTypeController extends Controller
{

    public function index(Request $request)
    {

        $search = $request->input('search');

        $query = KeyType::query();

        if ($search) {

            $query->where('name', 'like', "%{$search}%")

                ->orWhere('area', 'like', "%{$search}%")

                ->orWhere('email', 'like', "%{$search}%");

        }

        $keyTypes = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('key_type', compact('keyTypes'));

    }

    public function fetchAll(Request $request)
    {

        if ($request->ajax()) {

            $data = KeyType::select('id', 'name', 'area', 'email', 'created_at', 'updated_at');

            return DataTables::of($data)

                ->editColumn('created_at', function ($row) {

                    return Carbon::parse($row->created_at)->format('d/m/Y H:i');

                })

                ->editColumn('updated_at', function ($row) {

                    return Carbon::parse($row->updated_at)->format('d/m/Y H:i');

                })

                ->addColumn('action', function ($row) {
                    return '
                <a href="' . route('key_type.edit', $row->id) . '" class="btn btn-warning btn-sm me-1">Editar</a>
                <button class="btn btn-danger btn-sm delete-button" data-id="' . $row->id . '">Eliminar</button>
                    ';
                })

                ->rawColumns(['action'])

                ->make(true);

        }

    }

    public function create()
    {

        return view('add_key_type');

    }

    public function store(Request $request)
    {

        $request->validate([

            'name' => 'required|string|max:255',

            'area' => 'nullable|string|max:255',

            'email' => 'nullable|email|max:255',

        ]);

        KeyType::create($request->only(['name', 'area', 'email']));

        return redirect()->route('key_type.index')->with('success', 'Tipo de llave agregado correctamente.');

    }

    public function edit($id)
    {

        $keyType = KeyType::findOrFail($id);

        return view('edit_key_type', compact('keyType'));

    }

    public function update(Request $request, $id)
    {

        $request->validate([

            'name' => 'required|string|max:255',

            'area' => 'nullable|string|max:255',

            'email' => 'nullable|email|max:255',

        ]);

        $keyType = KeyType::findOrFail($id);

        $keyType->update($request->only(['name', 'area', 'email']));

        return redirect()->route('key_type.index')->with('success', 'Tipo de llave actualizado correctamente.');

    }

    public function destroy($id)
    {

        $keyType = KeyType::findOrFail($id);

        $keyType->delete();

        return redirect()->route('key_type.index')->with('success', 'Tipo de llave eliminado correctamente.');

    }

}

