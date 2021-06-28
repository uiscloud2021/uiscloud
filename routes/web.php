<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RecycledController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;

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

Route::resource('logs', LogController::class);

Route::resource('profile', ProfileController::class);


Route::post('/dashboard/list_files', 'App\Http\Controllers\DashController@list_files')->name('dashboard.list_files');
Route::post('/dashboard/delete_files', 'App\Http\Controllers\DashController@delete_files')->name('dashboard.delete_files');
Route::post('/dashboard/edit_files', 'App\Http\Controllers\DashController@edit_files')->name('dashboard.edit_files');
Route::post('/dashboard/details', 'App\Http\Controllers\DashController@details')->name('dashboard.details');
Route::post('/dashboard/created_files', 'App\Http\Controllers\DashController@created_files')->name('dashboard.created_files');
Route::post('/dashboard/download_files', 'App\Http\Controllers\DashController@download_files')->name('dashboard.download_files');
Route::post('/dashboard/update_files', 'App\Http\Controllers\DashController@update_files')->name('dashboard.update_files');
Route::post('/dashboard/comprimir_files', 'App\Http\Controllers\DashController@comprimir_files')->name('dashboard.comprimir_files');

Route::post('/dashboard/folderdetails', 'App\Http\Controllers\DashController@folderdetails')->name('dashboard.folderdetails');
Route::post('/dashboard/created_folder', 'App\Http\Controllers\DashController@created_folder')->name('dashboard.created_folder');
Route::post('/dashboard/edit_folder', 'App\Http\Controllers\DashController@edit_folder')->name('dashboard.edit_folder');
Route::post('/dashboard/update_folder', 'App\Http\Controllers\DashController@update_folder')->name('dashboard.update_folder');
Route::post('/dashboard/delete_folder', 'App\Http\Controllers\DashController@delete_folder')->name('dashboard.delete_folder');


Route::get('/dashboard', 'App\Http\Controllers\DashController@index')->name('dashboard');

/*Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');*/
