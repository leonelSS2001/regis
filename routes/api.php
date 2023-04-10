<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\DetallePedidoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('categorias',CategoriaController::class);
Route::resource('productos',ProductoController::class);
Route::resource('proveedores',ProveedorController::class);
Route::resource('clientes',ClienteController::class);
Route::resource('pedidos',PedidoController::class);
Route::resource('detalle',DetallePedidoController::class);
//Route::resource('home',HomeController::class);