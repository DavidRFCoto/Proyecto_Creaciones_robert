<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Produccion extends Model
{

    use HasFactory;
    protected $table = 'producciones';

    protected $fillable=[

        'codigo',
        'nombre',
        'descripcion',

        'persona_id',
        'user_id',

        'estado',

        'fecha_inicio',
        'fecha_finalizacion',

        'costo_materiales',
        'costo_mano_obra',
        'costo_total',

        'cantidad_prendas'
    ];

    public function persona()
    {
        return $this->belongsTo(
            Persona::class
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function detalles()
    {
        return $this->hasMany(
            DetalleProduccion::class
        );
    }
    public function detalleFacturas()
{
    return $this->hasMany(
        DetalleFactura::class
    );
}
}