<?php

use App\Http\Controllers\Empresa\EmpresaController;
use App\Http\Controllers\Galeria\GaleriaController;
use App\Http\Controllers\Paquete\PaqueteController;
use App\Http\Controllers\User\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('users', UserController::class)->except(['store', 'edit']);
Route::resource('empresas', EmpresaController::class)->except(['store', 'edit']);
Route::resource('galerias', GaleriaController::class)->except(['store', 'edit']);
Route::resource('paquetes', PaqueteController::class)->except(['store', 'edit']);
