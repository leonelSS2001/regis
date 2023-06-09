<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/admin', [HomeController::class, 'dash'])->name('dash');
//Route::resource('clientes', ClienteController::class)->middleware('auth.admin');
Route::resource('categorias', CategoriaController::class);
Route::resource('productos', ProductoController::class);
Route::resource('pedidos', PedidoController::class);
Route::resource('pro', ProveedorController::class);
Route::resource('det', DetallePedidoController::class);
//Route::get('/productos', [ProductoController::class, 'index']);
//Route::get('/pedidos', [PedidoController::class, 'index']);

