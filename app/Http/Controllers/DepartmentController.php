<?php

// Controlador actualizado: DepartmentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Department;

use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class DepartmentController extends Controller

{

    public function __construct()

    {

        $this->middleware('auth');

    }

    public function index()

    {

        return view('department');

    }

    function fetch_all(Request $request)

    {

        if ($request->ajax()) {

            $data = Department::latest()->get();

            return DataTables::of($data)

                ->addIndexColumn()

                ->addColumn('action', function ($row) {

                    return '<a href="/department/edit/'.$row->id.'" class="btn btn-primary btn-sm">Editar</a>&nbsp;<button type="button" class="btn btn-danger btn-sm delete" data-id="'.$row->id.'">Eliminar</button>';

                })

                ->addColumn('email', function ($row) {

                    return $row->email;

                })

                ->editColumn('created_at', function ($row) {

                    return Carbon::parse($row->created_at)->format('d/m/Y H:i');

                })

                ->editColumn('updated_at', function ($row) {

                    return Carbon::parse($row->updated_at)->format('d/m/Y H:i');

                })

                ->rawColumns(['action'])

                ->make(true);

        }

    }

    function add()

    {

        return view('add_department');

    }

    function add_validation(Request $request)

    {

        $request->validate([

            'department_name' => 'required',

            'contact_person' => 'required',

            'email' => 'required|email',

        ]);

        $data = $request->all();

        Department::create([

            'department_name' => $data['department_name'],

            'contact_person' => implode(", ", $data['contact_person']),

            'email' => $data['email'],

        ]);

        return redirect('department')->with('success', 'Nuevo departamento agregado');

    }

    public function edit($id)

    {

        $data = Department::findOrFail($id);

        return view('edit_department', compact('data'));

    }

    function edit_validation(Request $request)

    {

        $request->validate([

            'department_name' => 'required',

            'contact_person' => 'required',

            'email' => 'required|email',

        ]);

        $data = $request->all();

        $form_data = [

            'department_name' => $data['department_name'],

            'contact_person' => implode(", ", $data['contact_person']),

            'email' => $data['email'],

        ];

        Department::whereId($data['hidden_id'])->update($form_data);

        return redirect('department')->with('success', 'Datos del Departamento Actualizados');

    }

    function delete($id)

    {

        $data = Department::findOrFail($id);

        $data->delete();

        return redirect('department')->with('success', 'Datos del departamento eliminados');

    }

}

