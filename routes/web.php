<?php

use Illuminate\Support\Facades\Route;
use App\Models\Persona;

// Ruta principal: Redirige automáticamente al listado
Route::get('/', function () {
    return redirect('/listado-medidas');
});

// Tus otras rutas...
Route::get('/tomar-medidas', function () {
    return view('medidas'); 
});

//  ruta medidas con ID
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