<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\NewVisitor;
use App\Models\KeyLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Support\Facades\App;

class CustomAuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function custom_login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $data = $request->all();

        User::create([

            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'type' => 'Admin'
        ]);
        return redirect('registration')->with('success', 'Registro completo');
    }

    public function dashboard()
    {
        if (Auth::check()) {

            App::setLocale('es');
            Carbon::setLocale('es');
            setlocale(LC_TIME, 'es_ES.UTF-8');

            $visitsPerMonth = NewVisitor::select(
                DB::raw("DATE_FORMAT(visitor_enter_time, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
                ->where('visitor_enter_time', '>=', Carbon::now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            $labels = $visitsPerMonth->pluck('month')->map(function ($m) {
                return Carbon::createFromFormat('Y-m', $m)->translatedFormat('F Y');
            });

            $data = $visitsPerMonth->pluck('total');

            $keysPerMonth = KeyLog::select(
                DB::raw("DATE_FORMAT(key_taken_at, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
                ->where('key_taken_at', '>=', Carbon::now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            $key_chart_labels = $keysPerMonth->pluck('month')->map(function ($m) {
                return Carbon::createFromFormat('Y-m', $m)->translatedFormat('F Y');
            });

            $key_chart_data = $keysPerMonth->pluck('total');

            return view('dashboard', [
                'chart_labels' => $labels,
                'chart_data' => $data,
                'key_chart_labels' => $key_chart_labels,
                'key_chart_data' => $key_chart_data
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
