<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produccion;
use App\Models\DetalleProduccion;
use App\Models\InventarioMaterial;
use App\Models\InventarioPrenda;
use App\Models\MovimientoMaterial;
use App\Models\MovimientoPrenda;
use Illuminate\Support\Facades\DB;

class ProduccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
     return Produccion::with([
    'persona:id,nombre',
])->latest()->get();
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
     logger($request->all());
    DB::beginTransaction();

    try {

        // 1. CREAR PRODUCCIÓN
        $produccion = Produccion::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'persona_id' => $request->persona_id,
            'fecha_inicio' => $request->fecha_inicio,
            'cantidad_prendas' => $request->cantidad_prendas,
        ]);

        // 2. RECORRER DETALLES
        foreach ($request->detalles as $detalle) {

            // guardar detalle
            $detalleModel = DetalleProduccion::create([
                'produccion_id' => $produccion->id,
                'tipo' => $detalle['tipo'],
                'inventario_material_id' => $detalle['inventario_material_id'] ?? null,
                'inventario_prenda_id' => $detalle['inventario_prenda_id'] ?? null,
                'cantidad' => $detalle['cantidad'],
                'costo_unitario' => 0,
                'subtotal' => 0,
            ]);

            // 🔥 SOLO SI ES CONSUMO DE MATERIAL
            if ($detalle['tipo'] === 'consumo') {

                $material = InventarioMaterial::findOrFail(
                    $detalle['inventario_material_id']
                );

                $stockAnterior = $material->stock;

                // validar stock
                if ($stockAnterior < $detalle['cantidad']) {
                    throw new \Exception("Stock insuficiente en {$material->nombre}");
                }

                $stockNuevo = $stockAnterior - $detalle['cantidad'];

                // actualizar inventario
                $material->update([
                    'stock' => $stockNuevo
                ]);

                // registrar movimiento
                MovimientoMaterial::create([
                    'inventario_material_id' => $material->id,
                    'tipo' => 'salida',
                    'cantidad' => $detalle['cantidad'],
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $stockNuevo,
                    'motivo' => 'Consumo producción #' . $produccion->codigo,
                    'referencia' => $produccion->codigo,
                    'descripcion' => 'Consumo automático desde producción',
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'produccion' => $produccion
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produccion $produccione)
{
    DB::beginTransaction();

    try {

        $estadoAnterior = $produccione->estado;

        $produccione->update([
            'estado' => $request->estado,
        ]);

        // 🔥 SOLO SI PASA A FINALIZADA
        if (
            $estadoAnterior !== 'finalizada' &&
            $request->estado === 'finalizada'
        ) {

            foreach ($produccione->detalles as $detalle) {

                if ($detalle->tipo === 'produccion') {

                    $prenda = InventarioPrenda::findOrFail(
                        $detalle->inventario_prenda_id
                    );

                    $stockAnterior = $prenda->stock;

                    $stockNuevo = $stockAnterior + $detalle->cantidad;

                    // actualizar stock
                    $prenda->update([
                        'stock' => $stockNuevo
                    ]);

                    // registrar movimiento
                    MovimientoPrenda::create([
                        'inventario_prenda_id' => $prenda->id,
                        'tipo' => 'entrada',
                        'cantidad' => $detalle->cantidad,
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $stockNuevo,
                        'motivo' => 'Producción finalizada #' . $produccione->codigo,
                        'referencia' => $produccione->codigo,
                        'descripcion' => 'Ingreso automático por producción',
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'mensaje' => 'Producción actualizada'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
