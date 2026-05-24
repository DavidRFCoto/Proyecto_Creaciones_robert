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
// Ruta para el listado de medidas                          
Route::get('/listado-medidas', function () {
    return view('listado'); 
});// =========================
// REGISTRAR USUARIOS
// =========================
Route::get('/registrar-alumno', function () {
    return view('alumnos'); 
});
// =========================
// INVENTARIO MATERIALES
// =========================
Route::view(
    '/inventario-materiales',
    'materiales'
);

// =========================
// INVENTARIO PRENDAS
// =========================
Route::view(
'/inventario-prendas',
'prendas'
);
// =========================
// MOVIMIENTOS MATERIALES
// =========================
Route::view(
'/movimientos-materiales',
'movimientos-materiales'
);

    // =========================
// MOVIMIENTOS PRENDAS
// =========================
Route::view(
'/movimientos-prendas',
'movimientos-prendas'
);

// =========================
// PRODUCCIONES
// =========================
Route::view(
'/producciones',
'producciones'
);