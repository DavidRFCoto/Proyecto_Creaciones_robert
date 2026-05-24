<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovimientoPrenda extends Model
{
    use HasFactory;

     protected $table='movimientos_prendas';

    protected $fillable=[

        'inventario_prenda_id',
        'user_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'motivo',
        'referencia',
        'descripcion'

    ];

    public function prenda()
    {
        return $this->belongsTo(
            InventarioPrenda::class,
            'inventario_prenda_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}