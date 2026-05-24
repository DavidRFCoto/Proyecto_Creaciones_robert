<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_prendas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('inventario_prenda_id')
                  ->constrained('inventario_prendas')
                  ->cascadeOnDelete();

            $table->enum('tipo',[
                'entrada',
                'salida',
                'ajuste'
            ]);

            $table->integer('cantidad');

            $table->integer('stock_anterior');

            $table->integer('stock_nuevo');

            $table->string('motivo');

            $table->string('referencia')->nullable();

            $table->text('descripcion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_prendas');
    }
};