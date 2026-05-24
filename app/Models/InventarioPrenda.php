<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioPrenda extends Model
{
    protected $table='inventario_prendas';

    protected $fillable=[
        'nombre',
'tipo_prenda',
'categoria',
'talla',
'sexo',
'color',
'stock',
'costo_produccion',
'precio_venta',
'motivo',
'descripcion'
    ];

   public function movimientos()
{
    return $this->hasMany(MovimientoPrenda::class);
}

public function detallesProduccion()
{
    return $this->hasMany(
        DetalleProduccion::class,
        'inventario_prenda_id'
    );
}

public function detalleFacturas()
{
    return $this->hasMany(
        DetalleFactura::class,
        'inventario_prenda_id'
    );
}
}