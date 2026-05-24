<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producciones', function (Blueprint $table) {

            $table->id();

            // código visible
            $table->string('codigo')->unique();

            // nombre corto
            $table->string('nombre');

            // descripción libre
            $table->text('descripcion')
                  ->nullable();

            /*
             pendiente
             proceso
             finalizada
             cancelada
            */
            $table->enum('estado',[
                'pendiente',
                'proceso',
                'finalizada',
                'cancelada'
            ])->default('pendiente');

            // cliente o institución
            $table->foreignId('persona_id')
                    ->nullable()
                    ->constrained('personas')
                    ->nullOnDelete();

                    // responsable o encargado
           $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // fecha planeada
            $table->date('fecha_inicio')->nullable();

            $table->date('fecha_finalizacion')->nullable();

            // costos calculados
            $table->decimal('costo_materiales',10,2)->default(0);

            $table->decimal('costo_mano_obra',10,2)->default(0);

            $table->decimal('costo_total',10,2)->default(0);

            $table->unsignedInteger('cantidad_prendas') ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producciones');
    }
};