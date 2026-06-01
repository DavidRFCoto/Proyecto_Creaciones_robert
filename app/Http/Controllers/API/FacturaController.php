<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\InventarioPrenda;
use App\Models\MovimientoPrenda;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Factura::with([
        'persona',
        'detalles.prenda'
    ])
    ->latest()
    ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    DB::beginTransaction();

    try {

        $subtotalFactura = 0;

        $factura = Factura::create([
            'numero' => $request->numero,
            'persona_id' => $request->persona_id,
            'cliente_nombre' => $request->cliente_nombre,
            'fecha' => $request->fecha,
            'estado' => 'pendiente',
            'observaciones' => $request->observaciones,
        ]);

        foreach ($request->detalles as $detalle) {

            $prenda = InventarioPrenda::findOrFail(
                $detalle['inventario_prenda_id']
            );

            $stockAnterior = $prenda->stock;

            if ($stockAnterior < $detalle['cantidad']) {
                throw new \Exception(
                    "Stock insuficiente de {$prenda->tipo_prenda}"
                );
            }

            $subtotalLinea =
                ($detalle['cantidad'] * $detalle['precio_unitario'])
                - ($detalle['descuento'] ?? 0);

            $ganancia =
                ($detalle['precio_unitario'] - $prenda->costo_produccion)
                * $detalle['cantidad'];

            DetalleFactura::create([
                'factura_id' => $factura->id,
                'inventario_prenda_id' => $prenda->id,
                'produccion_id' => $detalle['produccion_id'] ?? null,
                'descripcion' => $detalle['descripcion'],
                'talla' => $prenda->talla,
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'descuento' => $detalle['descuento'] ?? 0,
                'subtotal' => $subtotalLinea,
                'costo_unitario' => $prenda->costo_produccion,
                'ganancia' => $ganancia,
            ]);

            $stockNuevo =
                $stockAnterior - $detalle['cantidad'];

            $prenda->update([
                'stock' => $stockNuevo
            ]);

            MovimientoPrenda::create([
                'inventario_prenda_id' => $prenda->id,
                'tipo' => 'salida',
                'cantidad' => $detalle['cantidad'],
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $stockNuevo,
                'motivo' => 'Venta factura #' . $factura->numero,
                'referencia' => $factura->numero,
                'descripcion' => 'Salida automática por venta',
            ]);

            $subtotalFactura += $subtotalLinea;
        }

        $impuesto = $subtotalFactura * 0.13;

        $total =
            $subtotalFactura
            - ($request->descuento ?? 0)
            + $impuesto;

        $factura->update([
            'subtotal' => $subtotalFactura,
            'descuento' => $request->descuento ?? 0,
            'impuesto' => $impuesto,
            'total' => $total,
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'factura' => $factura
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
