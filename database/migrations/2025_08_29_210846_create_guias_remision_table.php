<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guias_remision', function (Blueprint $table) {
            $table->id();
            $table->string('serie_correlativo')->unique();
            $table->enum('tipo', ['Entrada', 'Salida']); // Entrada (de proveedor), Salida (a cliente)
            
            // Se vincula a una orden de compra o una de venta, pero no a ambas.
            $table->foreignId('orden_compra_id')->nullable()->constrained('ordenes_compra');
            $table->foreignId('orden_venta_id')->nullable()->constrained('ordenes_venta');
            
            $table->date('fecha_emision');
            $table->date('fecha_traslado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guias_remision');
    }
};
