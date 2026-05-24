<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_factura', function (Blueprint $table) {

            $table->id();

            // factura padre
            $table->foreignId('factura_id')
                  ->constrained('facturas')
                  ->cascadeOnDelete();

            /*
             Si se vende una prenda del inventario
            */
            $table->foreignId('inventario_prenda_id')
                  ->nullable()
                  ->constrained('inventario_prendas')
                  ->nullOnDelete();

            /*
             Si viene de una producción específica
            */
            $table->foreignId('produccion_id')
                  ->nullable()
                  ->constrained('producciones')
                  ->nullOnDelete();

            // nombre histórico
            $table->string('descripcion');

            // talla vendida
            $table->string('talla')
                  ->nullable();

            // cantidad vendida
            $table->unsignedInteger('cantidad')
                  ->default(1);

            // precio por unidad
            $table->decimal('precio_unitario',10,2);

            // descuento por línea
            $table->decimal('descuento',10,2)
                  ->default(0);

            // subtotal calculado
            $table->decimal('subtotal',10,2);

            // costo real
            $table->decimal('costo_unitario',10,2)
                  ->default(0);

            // ganancia automática
            $table->decimal('ganancia',10,2)
                  ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_factura');
    }
};