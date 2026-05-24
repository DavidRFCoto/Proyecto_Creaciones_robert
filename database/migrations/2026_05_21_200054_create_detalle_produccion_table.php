<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_produccion', function (Blueprint $table) {

            $table->id();

            $table->foreignId('produccion_id')
                  ->constrained('producciones')
                  ->cascadeOnDelete();

            // material usado
            $table->foreignId('inventario_material_id')
                  ->nullable()
                  ->constrained('inventario_materiales')
                  ->nullOnDelete();

            // producto generado
            $table->foreignId('inventario_prenda_id')
                  ->nullable()
                  ->constrained('inventario_prendas')
                  ->nullOnDelete();

            /*
              consumo
              producción
            */
            $table->enum('tipo',[
                'consumo',
                'produccion'
            ]);

            $table->decimal('cantidad',10,2);

            $table->decimal('costo_unitario',10,2)
                  ->default(0);

            $table->decimal('subtotal',10,2)
                  ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_produccion');
    }
};