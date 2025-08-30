<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrdenVenta extends Model
{
    use HasFactory;

    protected $table = 'detalles_orden_venta';

    protected $fillable = [
        'orden_venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario'
    ];

    public function ordenVenta()
    {
        return $this->belongsTo(OrdenVenta::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
