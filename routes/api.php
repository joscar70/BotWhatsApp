<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::get('getAutenticacion', [App\Http\Controllers\UsuariosCtr::class, 'login'])->name('login');
    
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        
        Route::post('getLogout', [App\Http\Controllers\UsuariosCtr::class, 'logout'])->name('logout');
        Route::post('sendMensaje', [App\Http\Controllers\EnviarMensajeCtr::class, 'sendMensaje'])->name('sendMensaje');


        
    });
});

Route::post('jCwhatsAppBot', [App\Http\Controllers\ApiWhatsAppBotCtr::class,'__construct']);