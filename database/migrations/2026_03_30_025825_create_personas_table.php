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
    Schema::create('personas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre'); // Para clientes, alumnos o empleados
        $table->enum('sexo', ['Masculino', 'Femenino']); // Vital para el procesamiento de tallas
        $table->string('grupo_grado'); // Para organizar por grados (escuelas) o departamentos (empresas)
        $table->timestamps(); // Esto cumple con el requisito de fecha y hora en tiempo real
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
