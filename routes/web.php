<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RecycledController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashController;

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
    return view('auth.login');
});

//PARA PROTEGER LAS RUTAS POR LOS ROLES DEPENDIENDO DE LOS PERMISOS DE LOS USUARIOS
//Route::resource('files', FileController::class)->middleware('can:files.index');

Route::resource('files', FileController::class);

Route::resource('users', UserController::class);

Route::resource('roles', RoleController::class);

Route::resource('categories', CategoryController::class);

Route::resource('recycleds', RecycledController::class);

Route::resource('home', InicioController::class);

Route::resource('dashboard', DashController::class);

Route::get('/dashboard', 'App\Http\Controllers\DashController@index')->name('dashboard');

/*Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');*/
