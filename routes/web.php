<?php

use Illuminate\Support\Facades\Route;

// Ruta principal: Redirige automáticamente al listado
Route::get('/', function () {
    return redirect('/listado-medidas');
});

// Tus otras rutas...
Route::get('/tomar-medidas', function () {
    return view('medidas'); 
});

Route::get('/listado-medidas', function () {
    return view('listado'); 
});
Route::get('/registrar-alumno', function () {
    return view('alumnos'); 
});