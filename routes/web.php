<?php

use Illuminate\Support\Facades\Route;
use App\Models\Persona;
use App\Http\Controllers\AuthController;

// =========================
// 🔓 RUTAS PÚBLICAS 
// =========================
Route::view('/login', 'login')->name('login');
Route::post('/iniciar-sesion', [AuthController::class, 'login'])->name('login.manual');
Route::post('/iniciar-sesion-google', [AuthController::class, 'loginGoogle']); 

Route::view('/registro', 'registro')->name('registro');
Route::post('/registrar-manual', [AuthController::class, 'register'])->name('register.manual');
Route::post('/registrar-google', [AuthController::class, 'registerGoogle']);

// =========================
// 🔒 RUTAS PROTEGIDAS (Solo usuarios logueados)
// =========================
Route::middleware('auth')->group(function () {

    // El cierre de sesión se pasa para acá adentro
    Route::post('/cerrar-sesion', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect('/listado-medidas');
    });

    Route::get('/tomar-medidas', function () {
        return view('medidas'); 
    });

    Route::get('/tomar-medidas/{id}', function ($id) {
        $persona = Persona::findOrFail($id);
        return view('medidas', compact('persona'));
    });

    Route::get('/listado-medidas', function () {
        return view('listado'); 
    });

    Route::get('/registrar-alumno', function () {
        return view('alumnos'); 
    });

    Route::view('/inventario-materiales', 'materiales');
    Route::view('/inventario-prendas', 'prendas');
    Route::view('/movimientos-materiales', 'movimientos-materiales');
    Route::view('/movimientos-prendas', 'movimientos-prendas');
    Route::view('/producciones', 'producciones');
});