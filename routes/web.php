<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\RolesCtr;
use App\Http\Livewire\PermisosCtr;
use App\Http\Livewire\AsignarPermisosCtr;
use App\Http\Livewire\UsuariosCtr;


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
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('roles',RolesCtr::class);
Route::get('permisos',PermisosCtr::class);
Route::get('asignarPermisos',AsignarPermisosCtr::class);
Route::get('usuarios',UsuariosCtr::class);
