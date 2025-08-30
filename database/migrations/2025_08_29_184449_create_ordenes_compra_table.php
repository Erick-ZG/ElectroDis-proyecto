<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_compra', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('restrict');
            // Usuario que solicita (Gerente de Almacén)
            $table->foreignId('solicitado_por')->constrained('users')->onDelete('restrict');
            // Usuario que aprueba (Gerente de Compras)
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->onDelete('restrict');
            $table->enum('estado', ['Solicitada', 'Aprobada', 'Rechazada', 'Cancelada', 'Recibida Parcialmente', 'Recibida Completa']);
            $table->text('motivo_solicitud')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->date('fecha_entrega_esperada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_compra');
    }
};
