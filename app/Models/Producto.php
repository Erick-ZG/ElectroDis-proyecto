<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'nombre', 'descripcion', 'categoria_id',
        'precio_venta', 'stock_minimo', 'ubicacion'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relación con Inventario
    public function inventario()
    {
        return $this->hasOne(Inventario::class);
    }

    // Acceso indirecto a los movimientos a través del inventario
    public function movimientos()
    {
        return $this->hasManyThrough(
            MovimientoInventario::class,
            Inventario::class,
            'producto_id',      // Foreign key en inventarios
            'inventario_id',    // Foreign key en movimientos
            'id',               // Local key en productos
            'id'                // Local key en inventarios
        );
    }
}
