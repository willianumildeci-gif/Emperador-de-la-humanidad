<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleadoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/productos', fn () => view('section', ['title' => 'Producto']))->name('productos');
    Route::get('/clientes', fn () => view('section', ['title' => 'Clientes']))->name('clientes');
    Route::get('/ventas/nueva', fn () => view('section', ['title' => 'Registrar Venta']))->name('ventas.create');

    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
    Route::get('/empleados/{id}/editar', [EmpleadoController::class, 'edit'])->name('empleados.edit');
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
    Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');
    Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');
});
