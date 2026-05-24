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
       Schema::create('inventario_prendas', function (Blueprint $table) {

    $table->id();

    $table->enum('tipo_prenda',[
        'camisa',
        'pantalon',
        'falda'
    ]);

    
    $table->string('talla');

    $table->string('color')->nullable();

    $table->integer('stock')->default(0);

    $table->decimal('costo_produccion',10,2)->nullable();

    $table->decimal('precio_venta',10,2)->nullable();

    $table->string('motivo')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_prendas');
    }
};
