<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notas_credito', function (Blueprint $table) {
            $table->id();
            $table->string('serie_correlativo')->unique();
            // Vinculada al comprobante original que se está rectificando
            $table->foreignId('comprobante_id')->constrained('comprobantes');
            $table->foreignId('reclamo_id')->nullable()->constrained('reclamos');
            $table->decimal('monto', 12, 2);
            $table->text('motivo');
            $table->date('fecha_emision');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas_credito');
    }
};
