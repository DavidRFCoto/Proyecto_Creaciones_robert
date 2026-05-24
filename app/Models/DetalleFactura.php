<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    protected $table='detalle_factura';

    protected $fillable=[

        'factura_id',

        'inventario_prenda_id',

        'produccion_id',

        'descripcion',

        'talla',

        'cantidad',

        'precio_unitario',

        'descuento',

        'subtotal',

        'costo_unitario',

        'ganancia'
    ];

    public function factura()
    {
        return $this->belongsTo(
            Factura::class
        );
    }

    public function prenda()
    {
        return $this->belongsTo(
            InventarioPrenda::class,
            'inventario_prenda_id'
        );
    }

   public function produccion()
{
    return $this->belongsTo(
        Produccion::class,
        'produccion_id'
    );
}
}
