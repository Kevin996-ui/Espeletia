<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Session;

use App\Models\NewVisitor;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class CustomAuthController extends Controller

{

    public function index()

    {

        return view('auth.login');
    }

    public function custom_login(Request $request)

    {

        $request->validate([

            'email'     =>  'required',

            'password'  =>  'required'

        ]);

        $credential = $request->only('email', 'password');

        if (Auth::attempt($credential)) {

            return redirect()->intended('dashboard')->withSuccess('Login');
        }

        return redirect('login')->with('error', 'Los datos de inicio de sesión no son válidos');
    }

    public function registration()

    {

        return view('auth.registration');
    }

    public function custom_registration(Request $request)

    {

        $request->validate([

            'name'      =>  'required',

            'email'     =>  'required|email|unique:users',

            'password'  =>  'required|min:6'

        ]);

        $data = $request->all();

        User::create([

            'name'      =>  $data['name'],

            'email'     =>  $data['email'],

            'password'  =>  Hash::make($data['password']),

            'type'      =>  'Admin'

        ]);

        return redirect('registration')->with('success', 'Registro completo');
    }

    public function dashboard()

    {

        if (Auth::check()) {

            $visitsPerDay = NewVisitor::select(DB::raw('DATE(visitor_enter_time) as date'), DB::raw('count(*) as total'))

                ->where('visitor_enter_time', '>=', Carbon::now()->subDays(30))

                ->groupBy('date')

                ->orderBy('date', 'asc')

                ->get();

            $labels = $visitsPerDay->pluck('date')->map(function ($d) {

                return Carbon::parse($d)->format('d M');
            });

            $data = $visitsPerDay->pluck('total');

            return view('dashboard', [

                'chart_labels' => $labels,

                'chart_data' => $data,

            ]);
        }

        return redirect('login');
    }

    public function logout()

    {

        Session::flush();

        Auth::logout();

        return redirect('login');
    }
}
