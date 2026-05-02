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
    Schema::table('personas', function (Blueprint $table) {
        $table->enum('tipo', ['alumno', 'cliente', 'empresa'])
        ->default('cliente')
        ->after('nombre');

        $table->string('nombre_empresa')->nullable()->after('tipo');
    });
}

public function down(): void
{
    Schema::table('personas', function (Blueprint $table) {
        $table->dropColumn(['tipo', 'nombre_empresa']);
    });
}

};
