<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sub_user');
    }

    function fetch_all(Request $request)
    {

        if ($request->ajax()) {
            $data = User::whereIn('type', ['User', 'Supervisor'])->get();
            return DataTables::of($data)

                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    return $row->type === 'Supervisor' ? 'Supervisor' : 'Sub Usuario';
                })

                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y H:i');
                })

                ->editColumn('updated_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('d/m/Y H:i');
                })

                ->addColumn('action', function ($row) {
                    return '<a href="/sub_user/edit/' . $row->id . '" class="btn btn-primary btn-sm">Editar</a>&nbsp;
<button type="button" class="btn btn-danger btn-sm delete" data-id="' . $row->id . '">Eliminar</button>';
                })

                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function add()
    {
        return view('add_sub_user');
    }

    function add_validation(Request $request)
    {
        $request->validate([

            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'type'     => 'required|in:User,Supervisor'
        ]);

        $data = $request->all();

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'type'     => $data['type']
        ]);
        return redirect('sub_user')->with('success', 'Nuevo usuario agregado');
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);
        return view('edit_sub_user', compact('data'));
    }

    function edit_validation(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name'  => 'required',
            'type'  => 'required|in:User,Supervisor'
        ]);

        $data = $request->all();
        $form_data = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'type'  => $data['type']
        ];

        if (!empty($data['password'])) {
            $form_data['password'] = Hash::make($data['password']);
        }

        User::whereId($data['hidden_id'])->update($form_data);
        return redirect('sub_user')->with('success', 'Datos de usuario actualizados');
    }

    function delete($id)
    {
        $data = User::findOrFail($id);
        $data->delete();
        return redirect('sub_user')->with('success', 'Usuario eliminado');
    }
}
