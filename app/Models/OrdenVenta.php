<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenVenta extends Model
{
    use HasFactory;

    protected $table = 'ordenes_venta';

    protected $fillable = [
        'cliente_id',
        'fecha',
        'estado',
        'monto_total'
    ];

    // Una orden de venta pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Una orden de venta tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(DetalleOrdenVenta::class);
    }

    // Relación con movimientos de inventario (cuando se despacha la venta)
    public function movimientosInventario()
    {
        return $this->hasMany(MovimientoInventario::class);
    }
}
