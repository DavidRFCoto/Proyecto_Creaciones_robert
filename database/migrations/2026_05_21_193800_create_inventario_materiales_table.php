<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('inventario_materiales', function (Blueprint $table) {

    $table->id();

    // Nombre visible
    $table->string('nombre');

    // Tela, hilo, botón, cierre, elástico...
    $table->string('categoria');

    // azul, negro, blanco...
    $table->string('color')->nullable();

    // marca/proveedor
    $table->string('marca')->nullable();

    // cantidad actual
    $table->decimal('stock',10,2)->default(0);

    /*
      metros
      unidades
      rollos
      paquetes
      yardas
    */
    $table->string('unidad_medida');

    // nivel mínimo antes de alerta
    $table->decimal('stock_minimo',10,2)
          ->default(5);

    // costo compra
    $table->decimal('precio_compra',10,2)
          ->nullable();

    // precio venta si aplica
    $table->decimal('precio_venta',10,2)
          ->nullable();

    // proveedor
    $table->string('proveedor')
          ->nullable();

    // factura o lote
    $table->string('lote')
          ->nullable();

    // fecha de ingreso
    $table->date('fecha_ingreso')
          ->nullable();

    // comentarios
    $table->text('descripcion')
          ->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_materiales');
    }
};
