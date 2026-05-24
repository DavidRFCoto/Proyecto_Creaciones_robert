<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // movimientos de materiales
    public function movimientosMateriales()
    {
        return $this->hasMany(MovimientoMaterial::class);
    }

    // movimientos de prendas
    public function movimientosPrendas()
    {
        return $this->hasMany(MovimientoPrenda::class);
    }

    // producciones
    public function producciones()
    {
        return $this->hasMany(Produccion::class);
    }
}
