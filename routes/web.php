<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubUserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\VisitorController;

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
    return view('auth.registration');
    //return view('auth.login');
});


//Rutas de autenticación

Route::get('registration', [CustomAuthController::class, 'registration'])->name('register');

Route::post('custom-registration', [CustomAuthController::class, 'custom_registration'])->name('register.custom');

Route::get('login', [CustomAuthController::class, 'index'])->name('login');

Route::post('custom-login', [CustomAuthController::class, 'custom_login'])->name('login.custom');

Route::get('dashboard', [CustomAuthController::class, 'dashboard'])->name('dashboard');

Route::get('logout', [CustomAuthController::class, 'logout'])->name('logout');

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


// Rutas de visitantes

Route::get('visitor', [VisitorController::class, 'index'])->name('visitor.index');

Route::get('visitor/create', [VisitorController::class, 'create'])->name('visitor.create');

Route::post('visitor/store', [VisitorController::class, 'store'])->name('visitor.store');

Route::get('visitor/delete/{id}', [VisitorController::class, 'delete'])->name('visitor.delete');

Route::get('visitor/fetch_all', [VisitorController::class, 'fetch_all'])->name('visitor.fetchall');

Route::get('visitor/add', [VisitorController::class, 'create'])->name('visitor.add');

Route::get('visitor/fetch_all', [VisitorController::class, 'fetch_all'])->name('visitor.fetch_all');
