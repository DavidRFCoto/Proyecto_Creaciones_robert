<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_materiales', function (Blueprint $table) {

            $table->id();

            // material afectado
            $table->foreignId('inventario_material_id')
                  ->constrained('inventario_materiales')
                  ->cascadeOnDelete();

            // entrada/salida/ajuste
            $table->enum('tipo', [
                'entrada',
                'salida',
                'ajuste'
            ]);

            // cantidad movida
            $table->decimal('cantidad',10,2);

            // stock antes del cambio
            $table->decimal('stock_anterior',10,2);

            // stock resultante
            $table->decimal('stock_nuevo',10,2);

            // compra, producción, devolución...
            $table->string('motivo');

            // factura, lote, compra #123
            $table->string('referencia')
                  ->nullable();

            // observaciones
            $table->text('descripcion')
                  ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_materiales');
    }
};