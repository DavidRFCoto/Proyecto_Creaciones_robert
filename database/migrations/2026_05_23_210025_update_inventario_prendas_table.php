<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'inventario_prendas',
            function (Blueprint $table) {

            // nombre visible
            $table->string('nombre')
                  ->after('id');

            // escolar, empresarial, deportivo
            $table->string('categoria')
                  ->nullable()
                  ->after('tipo_prenda');

            // masculino/femenino/unisex
            $table->string('sexo')
                  ->nullable()
                  ->after('talla');

            // observaciones
            $table->text('descripcion')
                  ->nullable()
                  ->after('motivo');

        });
    }

    public function down(): void
    {
        Schema::table(
            'inventario_prendas',
            function (Blueprint $table) {

            $table->dropColumn([
                'nombre',
                'categoria',
                'sexo',
                'descripcion'
            ]);

        });
    }
};