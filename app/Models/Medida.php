<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medida extends Model
{
    use HasFactory;

   protected $fillable = [
    'persona_id',
    'largo_camisa',
    'hombro', 
    'pecho',        
    'largo_pantalon',
    'cintura',
    'cadera',
    'rodilla',
    'ruedo',
    'largo_falda',
    'talla_sugerida'
];
    // Relación inversa: Una medida pertenece a una persona
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}