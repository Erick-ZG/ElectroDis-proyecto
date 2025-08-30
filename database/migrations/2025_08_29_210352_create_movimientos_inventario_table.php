<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');
            $table->enum('tipo_movimiento', ['Entrada', 'Salida']);
            // Motivo: Compra, Venta, Devolución de cliente, Devolución a proveedor, Ajuste
            $table->string('motivo');
            $table->integer('cantidad'); // Positivo para entradas, negativo para salidas
            $table->foreignId('user_id')->comment('Usuario que registra el movimiento')->constrained('users');
            
            // Referencia al documento que origina el movimiento
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->string('referencia_tipo')->nullable(); // Ej: App\Models\OrdenCompra, App\Models\OrdenVenta

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
