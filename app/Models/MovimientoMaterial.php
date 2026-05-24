<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovimientoMaterial extends Model
{
    use HasFactory;

    protected $table='movimientos_materiales';

    protected $fillable=[

'inventario_material_id',
'user_id',
'tipo',
'cantidad',
'stock_anterior',
'stock_nuevo',
'motivo',
'referencia',
'descripcion'

];

    public function material()
    {
        return $this->belongsTo(
            InventarioMaterial::class,
            'inventario_material_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            User::class
        );
    }
}