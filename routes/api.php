<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\MedidaController;
use App\Http\Controllers\API\PersonaController;
use App\Http\Controllers\API\InventarioMaterialController;
use App\Http\Controllers\API\InventarioPrendaController;
use App\Http\Controllers\API\MovimientoMaterialController;
use App\Http\Controllers\API\MovimientoPrendaController;
use App\Http\Controllers\API\ProduccionController;


/*
|--------------------------------------------------------------------------
| API Routes - Sistema SIGETEX (Creaciones Robert)
|--------------------------------------------------------------------------
*/


// =========================
// PERSONAS
// =========================

Route::apiResource(
    'personas',
    PersonaController::class
);


// =========================
// MEDIDAS
// =========================

Route::apiResource(
    'medidas',
    MedidaController::class
);


// =========================
// REPORTES
// =========================

Route::get(
    '/reporte/talla/{valor}',
    [MedidaController::class,'reportePorTalla']
);


Route::get(
    '/personas/{id}/medidas',
    [MedidaController::class,'showByPersona']
);


// =========================
// INVENTARIO MATERIALES
// =========================

Route::apiResource(
    'inventario-materiales',
    InventarioMaterialController::class
);

// =========================
// INVENTARIO PRENDAS
// =========================

Route::apiResource(
    'inventario-prendas',
    InventarioPrendaController::class
);

// =========================
// MOVIMIENTOS MATERIALES
// =========================
Route::apiResource(
    'movimientos-materiales',
    MovimientoMaterialController::class
);


// =========================
// MOVIMIENTOS PRENDAS
// =========================
Route::apiResource(
'movimientos-prendas',
MovimientoPrendaController::class
);

// =========================
// PRODUCCIONES
// =========================
Route::apiResource('producciones', ProduccionController::class);