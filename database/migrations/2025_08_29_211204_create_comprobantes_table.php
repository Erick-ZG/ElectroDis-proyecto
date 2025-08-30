<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_venta_id')->constrained('ordenes_venta');
            $table->string('serie_correlativo')->unique();
            $table->enum('tipo', ['Factura', 'Boleta']);
            $table->enum('estado', ['Emitida', 'Pagada', 'Anulada']);
            $table->decimal('total', 12, 2);
            $table->date('fecha_emision');
            $table->date('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
