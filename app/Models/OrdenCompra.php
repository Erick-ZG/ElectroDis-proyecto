<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'ordenes_compra';

    protected $fillable = [
        'proveedor_id',
        'fecha',
        'estado',
        'monto_total'
    ];

    // Una orden de compra pertenece a un proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // Una orden de compra tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(DetalleOrdenCompra::class);
    }

    // Relación con movimientos de inventario (cuando se recibe la compra)
    public function movimientosInventario()
    {
        return $this->hasMany(MovimientoInventario::class);
    }
}
