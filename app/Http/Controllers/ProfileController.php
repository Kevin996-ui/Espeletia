<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = User::findOrFail(Auth::user()->id);
        return view('admin_profile', compact('data'));
    }

    public function create()
    {
        return view('admin_profile_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:12|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->type = 'Admin';
        $user->save();

        return redirect()->route('admin_profile.index')->with('success', 'Administrador creado exitosamente.');
    }

    public function list()
    {
        return view('admin_profile');
    }

    public function fetchAll(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('type', 'Admin')->get();

            return DataTables::of($data)
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d/m/Y H:i');
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at->format('d/m/Y H:i');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin_profile.edit', ['id' => $row->id]);
                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-primary">Editar</a>
                        <button class="btn btn-sm btn-danger delete" data-id="' . $row->id . '">Eliminar</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function edit($id)
    {
        $user = Auth::user();

        if ($user->type !== 'Admin') {
            abort(403, 'No autorizado');
        }

        $data = User::findOrFail($id);

        if ($data->type !== 'Admin') {
            abort(403, 'Solo se pueden editar perfiles de administradores');
        }

        return view('profile', compact('data'));
    }

    public function edit_validation(Request $request)
    {
        $user = Auth::user();

        if ($user->type !== 'Admin') {
            abort(403, 'No autorizado');
        }

        $target = User::findOrFail($request->id);

        if ($target->type !== 'Admin') {
            abort(403, 'Solo se pueden editar perfiles de administradores');
        }

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $request->id,
            'name' => 'required'
        ]);

        $form_data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if (!empty($request->password)) {
            $form_data['password'] = Hash::make($request->password);
        }

        User::whereId($request->id)->update($form_data);

        return redirect()->route('profile')->with('success', 'Datos de perfil actualizados');
    }

    public function delete($id)
    {
        $auth = Auth::user();

        if ($auth->type !== 'Admin') {
            abort(403, 'No autorizado');
        }

        if ($auth->id == $id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propio perfil.'
            ], 403);
        }

        $user = User::where('type', 'Admin')->findOrFail($id);

        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Perfil eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar el perfil. Inténtalo de nuevo.'
            ], 500);
        }
    }

}
