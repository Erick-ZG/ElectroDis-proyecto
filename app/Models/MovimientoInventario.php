<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'inventario_id',
        'tipo_movimiento', // entrada o salida
        'cantidad',
        'descripcion',
        'fecha',
    ];

    // Relación con Inventario
    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
}
