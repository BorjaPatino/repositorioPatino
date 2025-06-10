<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\UsuarioController;

// Página de inicio
Route::get('/', function () {
    return redirect()->route('productos.index');
})->name('inicio');

// Rutas de autenticación
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/sobre-mi', function () {
    return view('sobre-mi');
})->name('sobre-mi');

Route::get('/productos-filtrados', [ProductoController::class, 'filtrarPorCategoria'])->name('productos.filtrar');


// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

    // Todos los usuarios logueados pueden ver el listado de productos
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');

    // Rutas para pedidos disponibles para cualquier usuario autenticado
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/create', [PedidoController::class, 'create'])->name('pedidos.create');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::delete('/pedidos/{id}', [PedidoController::class, 'destroy'])->name('pedidos.destroy');

    

    // CRUD completo de productos (excepto index)
    Route::resource('productos', ProductoController::class)->except(['index']);

    // CRUD completo de categorías
    Route::resource('categorias', CategoriaController::class);

    // Editar y actualizar pedidos (solo admin)
    Route::get('/pedidos/{id}/edit', [PedidoController::class, 'edit'])->name('pedidos.edit');
    Route::put('/pedidos/{id}', [PedidoController::class, 'update'])->name('pedidos.update');

    // CRUD completo de usuarios (solo admin)
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    
});
