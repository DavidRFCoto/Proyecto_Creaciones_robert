<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventarioMaterial extends Model
{   
     use HasFactory;

    protected $table='inventario_materiales';

    protected $fillable=[
        
    'nombre',
    'categoria',
    'color',
    'marca',
    'stock',
    'unidad_medida',
    'stock_minimo',
    'precio_compra',
    'precio_venta',
    'proveedor',
    'lote',
    'fecha_ingreso',
    'descripcion'
    ];

    public function movimientos()
{
    return $this->hasMany(MovimientoMaterial::class);
}

public function detallesProduccion()
{
    return $this->hasMany(
        DetalleProduccion::class,
        'inventario_material_id'
    );
}
}