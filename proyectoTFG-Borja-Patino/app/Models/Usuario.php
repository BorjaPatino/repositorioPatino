<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;

    protected $fillable = [
        'nombre',
        'email',
        'contraseña',
        'rol',
        'fecha_registro',
    ];

    protected $hidden = [
        'contraseña',
    ];

    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}