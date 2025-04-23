<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubUserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\RatingController;
use App\Models\NewVisitor;
use App\Http\Controllers\TestEmailController;
use App\Http\Controllers\KeyLogController;
use App\Http\Controllers\KeyTypeController;
use App\Http\Controllers\CardController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    //return view('auth.registration');
    return view('auth.login');
});

Route::get('/acceso-user', function () {
    session(['user_guest_type' => 'User']);
    return redirect('/dashboard-user');
});

Route::get('/dashboard-user', function () {
    return view('user_dashboard');
});

//Rutas de autenticación

Route::get('registration', [CustomAuthController::class, 'registration'])->name('register');
Route::post('custom-registration', [CustomAuthController::class, 'custom_registration'])->name('register.custom');
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'custom_login'])->name('login.custom');
Route::get('dashboard', [CustomAuthController::class, 'dashboard'])->name('dashboard');
Route::get('logout', [CustomAuthController::class, 'logout'])->name('logout');
Route::get('/register', function () {
    return view('auth.registration');
})->name('register.custom.view');

//Rutas de perfil

Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('profile/edit_validation', [ProfileController::class, 'edit_validation'])->name('profile.edit_validation');

// Rutas de sub-usuarios

Route::get('sub_user', [SubUserController::class, 'index'])->name('sub_user');
Route::get('sub_user/fetchall', [SubUserController::class, 'fetch_all'])->name('sub_user.fetchall');
Route::get('sub_user/add', [SubUserController::class, 'add'])->name('sub_user.add');
Route::post('sub_user/add_validation', [SubUserController::class, 'add_validation'])->name('sub_user.add_validation');
Route::get('sub_user/edit/{id}', [SubUserController::class, 'edit'])->name('edit');
Route::post('sub_user/edit_validation', [SubUserController::class, 'edit_validation'])->name('sub_user.edit_validation');
Route::get('sub_user/delete/{id}', [SubUserController::class, 'delete'])->name('delete');

//Rutas de departamentos

Route::get('department', [DepartmentController::class, 'index'])->name('department');
Route::get('department/fetch_all', [DepartmentController::class, 'fetch_all'])->name('department.fetch_all');
Route::get('department/add', [DepartmentController::class, 'add'])->name('add');
Route::post('department/add_validation', [DepartmentController::class, 'add_validation'])->name('department.add_validation');
Route::get('department/edit/{id}', [DepartmentController::class, 'edit'])->name('edit');
Route::post('department/edit_validation', [DepartmentController::class, 'edit_validation'])->name('department.edit_validation');
Route::get('department/delete/{id}', [DepartmentController::class, 'delete'])->name('delete');

// Rutas de tarjetas

Route::get('/card', [CardController::class, 'index'])->name('card.index');
Route::get('/card/fetch-all', [CardController::class, 'fetchAll'])->name('card.fetch_all');
Route::post('/card/add', [CardController::class, 'store'])->name('card.store');
Route::get('/card/add', [CardController::class, 'create'])->name('card.create');
Route::get('/card/edit/{id}', [CardController::class, 'edit'])->name('card.edit');
Route::post('/card/edit/{id}', [CardController::class, 'update'])->name('card.update');
Route::put('/card/edit/{id}', [CardController::class, 'update'])->name('card.update');
Route::get('/card/delete/{id}', [CardController::class, 'delete'])->name('card.delete');

// Rutas de visitantes

Route::get('/visitor', [VisitorController::class, 'index'])->name('visitor.index');
Route::get('/visitors', [VisitorController::class, 'index'])->name('visitor.index');
Route::get('/visitor/add', [VisitorController::class, 'add'])->name('visitor.add');
Route::post('/visitor/store', [VisitorController::class, 'store'])->name('visitor.store');
Route::get('/visitor/edit/{id}', [VisitorController::class, 'edit'])->name('visitor.edit');
Route::put('/visitor/update/{id}', [VisitorController::class, 'update'])->name('visitor.update');
Route::delete('/visitor/delete/{id}', [VisitorController::class, 'delete'])->name('visitor.delete');
Route::put('/visitor/update/{id}', [VisitorController::class, 'update'])->name('visitor.update');
Route::post('/visitor/{id}/exit', [VisitorController::class, 'registerExit'])->name('visitor.exit');
Route::post('/ratings', [RatingController::class, 'store']);
Route::get('/visitor/report', [VisitorController::class, 'showReportForm'])->name('visitor.report');
Route::get('/visitor/report/export/{format}', [VisitorController::class, 'exportReport'])->name('visitor.report.export');
Route::get('/visitor/ajax-search', [VisitorController::class, 'ajaxSearch'])->name('visitor.ajax-search');

//Test envío email

Route::get('/test-email', [TestEmailController::class, 'testEmail']);

//Registro Key

Route::get('/keylog', [KeyLogController::class, 'index'])->name('keylog.index');
Route::get('/keylog/create', [KeyLogController::class, 'create'])->name('keylog.create');
Route::post('/keylog', [KeyLogController::class, 'store'])->name('keylog.store');
Route::get('/keylog/{id}/edit', [KeyLogController::class, 'edit'])->name('keylog.edit');
Route::put('/keylog/{id}', [KeyLogController::class, 'update'])->name('keylog.update');
Route::delete('/keylog/{id}', [KeyLogController::class, 'destroy'])->name('keylog.destroy');
Route::get('/keylog/fetch_all', [KeyLogController::class, 'fetchAll'])->name('keylog.fetch_all');
Route::post('/keylog/return/{id}', [KeyLogController::class, 'registerReturn'])->name('keylog.return');
Route::get('/keylog/report', [KeyLogController::class, 'showReportForm'])->name('keylog.report');
Route::get('/keylog/report/export/{format}', [KeyLogController::class, 'exportReport'])->name('keylog.report.export');
Route::get('/keylog/ajax-search', [KeyLogController::class, 'ajaxSearch'])->name('keylog.ajax-search');

//Tipo de llave

Route::get('/key_type', [KeyTypeController::class, 'index'])->middleware('auth')->name('key_type.index');
Route::get('/key_type/fetch_all', [KeyTypeController::class, 'fetchAll'])->middleware('auth')->name('key_type.fetch_all');
Route::get('/key_type/add', [KeyTypeController::class, 'create'])->middleware('auth')->name('key_type.add');
Route::post('/key_type/store', [KeyTypeController::class, 'store'])->middleware('auth')->name('key_type.store');

Route::delete('/key_type/{id}', [KeyTypeController::class, 'destroy'])->name('key_type.destroy');

Route::get('/key_type/edit/{id}', [KeyTypeController::class, 'edit'])->middleware('auth')->name('key_type.edit');
Route::post('/key_type/update/{id}', [KeyTypeController::class, 'update'])->middleware('auth')->name('key_type.update');