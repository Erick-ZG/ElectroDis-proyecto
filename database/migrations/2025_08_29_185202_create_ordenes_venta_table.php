<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_venta', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('restrict');
            $table->foreignId('vendedor_id')->constrained('users')->onDelete('restrict');
            $table->enum('estado', ['Pendiente', 'Confirmada', 'Pagada', 'En Preparacion', 'Despachada', 'Entregada', 'Cancelada']);
            $table->enum('tipo_entrega', ['Recojo en tienda', 'Delivery']);
            $table->enum('condicion_pago', ['Contado', 'Credito']);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('impuestos', 12, 2);
            $table->decimal('total', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_venta');
    }
};
