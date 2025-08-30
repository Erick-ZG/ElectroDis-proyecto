<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre_completo', 'documento_identidad',
        'tipo_cliente', 'direccion', 'telefono', 'email'
    ];

    public function ordenesVenta()
    {
        return $this->hasMany(OrdenVenta::class);
    }
}
