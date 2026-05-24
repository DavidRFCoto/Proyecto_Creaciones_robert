<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {

            $table->id();

            // número visible
            $table->string('numero')
                  ->unique();

            // cliente
            $table->foreignId('persona_id')
                  ->constrained('personas')
                  ->cascadeOnDelete();

            $table->string('cliente_nombre');

            /*
             pendiente
             pagada
             anulada
            */
            $table->enum('estado',[
                'pendiente',
                'pagada',
                'anulada'
            ])->default('pendiente');

            $table->date('fecha');

            $table->decimal('subtotal',10,2)
                  ->default(0);

            $table->decimal('descuento',10,2)
                  ->default(0);

            $table->decimal('impuesto',10,2)
                  ->default(0);

            $table->decimal('total',10,2)
                  ->default(0);

            $table->text('observaciones')
                  ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};