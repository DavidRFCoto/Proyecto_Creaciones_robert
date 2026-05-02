<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Persona extends Model
{
    use HasFactory;

    // 1. Permitir la "Asignación Masiva" (Para que la API pueda guardar datos)
    protected $fillable = [
        'nombre', 
        'sexo',
        'tipo',
        'nombre_empresa', 
        'grupo_grado'
    ];

    // 2. Definir la Relación (Una persona tiene muchas medidas)
    public function medidas()
    {
        return $this->hasMany(Medida::class);
    }
}