<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable=[

        'numero',

        'persona_id',

        'cliente_nombre',

        'estado',

        'fecha',

        'subtotal',

        'descuento',

        'impuesto',

        'total',

        'observaciones'
    ];

    public function persona()
    {
        return $this->belongsTo(
            Persona::class
        );
    }

    public function detalles()
    {
        return $this->hasMany(
            DetalleFactura::class
        );
    }
}