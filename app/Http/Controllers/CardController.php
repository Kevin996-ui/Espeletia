<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CardController extends Controller

{
    public function index()
    {
        return view('card');
    }

    public function fetchAll(Request $request)
    {
        if ($request->ajax()) {
            $data = Card::select(['id', 'code', 'created_at', 'updated_at']);
            return DataTables::of($data)
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d/m/Y H:i');
                })

                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at->format('d/m/Y H:i');
                })

                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="/card/edit/' . $row->id . '" class="btn btn-primary btn-sm">Editar</a>';
                    $deleteBtn = ' <button type="button" class="btn btn-danger btn-sm delete" data-id="' . $row->id . '">Eliminar</button>';
                    return $editBtn . $deleteBtn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('add_card');
    }

    public function store(Request $request)
    {
        if (!$request->filled('code')) {
            return redirect()->back()->with('error', 'Debe ingresar un código válido.');
        }

        $request->validate([
            'code' => 'required|string|max:255|unique:cards,code',
        ]);

        Card::create([
            'code' => $request->code,
        ]);
        return redirect('/card')->with('success', 'Tarjeta agregada exitosamente.');
    }

    public function edit($id)
    {
        $card = Card::findOrFail($id);
        return view('edit_card', compact('card'));
    }

    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);
        $request->validate([
            'code' => 'required|string|max:255|unique:cards,code,' . $id,
        ]);

        $card->update([
            'code' => $request->code,
        ]);
        return redirect('/card')->with('success', 'Tarjeta actualizada exitosamente.');
    }

    public function delete($id)
    {
        $card = Card::findOrFail($id);
        $card->delete();
        return redirect('/card')->with('success', 'Tarjeta eliminada exitosamente.');
    }
}
