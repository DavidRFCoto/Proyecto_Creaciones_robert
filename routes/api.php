<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MedidaController;
use App\Http\Controllers\API\PersonaController;
// Tus rutas aquí abajo...

/*
|--------------------------------------------------------------------------
| API Routes - Sistema SIGETEX (Creaciones Robert)
|--------------------------------------------------------------------------
*/

// 1. Rutas Estándar (CRUD completo para Personas y Medidas)
Route::apiResource('personas', PersonaController::class);
Route::apiResource('medidas', MedidaController::class);

// 2. Rutas de Reportes Especializados
// Esta ruta permite buscar por talla (ej: /api/reporte/talla/32)
Route::get('/reporte/talla/{valor}', [MedidaController::class, 'reportePorTalla']);

// 3. Ruta Extra: Ver medidas de un alumno específico por su ID
// Útil para cuando David o un supervisor quieran ver el historial de alguien
Route::get('/personas/{id}/medidas', [MedidaController::class, 'showByPersona']);
// Nuevas rutas para Alumnos
Route::apiResource('personas', PersonaController::class);

//Route::get('/personas', [PersonaController::class, 'index']);
//Route::post('/personas', [PersonaController::class, 'store']);