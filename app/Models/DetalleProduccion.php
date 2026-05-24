<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DetalleProduccion extends Model
{
      use HasFactory;
    protected $table='detalle_produccion';

    protected $fillable=[

        'produccion_id',

        'inventario_material_id',

        'inventario_prenda_id',

        'tipo',

        'cantidad',

        'costo_unitario',

        'subtotal'
    ];

    public function produccion()
    {
        return $this->belongsTo(
            Produccion::class
        );
    }

    public function material()
    {
        return $this->belongsTo(
            InventarioMaterial::class,
            'inventario_material_id'
        );
    }

    public function prenda()
    {
        return $this->belongsTo(
            InventarioPrenda::class,
            'inventario_prenda_id'
        );
    }
}
