<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medidas', function (Blueprint $table) {
            $table->id();
            // Relación con la persona
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            
            // --- PARTE SUPERIOR (Anexo A) ---
            $table->decimal('hombro', 5, 2)->nullable(); // Antes era ancho_espalda
            $table->decimal('largo_camisa', 5, 2)->nullable();
            $table->decimal('pecho', 5, 2)->nullable(); // Agregado

            // --- PARTE INFERIOR (Anexos B, C y D) ---
            $table->decimal('cintura', 5, 2)->nullable();
            $table->decimal('cadera', 5, 2)->nullable();   // Agregado para Faldas y Pantalones
            $table->decimal('largo_pantalon', 5, 2)->nullable();
            $table->decimal('rodilla', 5, 2)->nullable();  // Agregado (Anexo B)
            $table->decimal('ruedo', 5, 2)->nullable();    // Agregado (Anexo B)
            $table->decimal('largo_falda', 5, 2)->nullable(); // Agregado

            // --- RESULTADO ---
            $table->string('talla_sugerida')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medidas');
    }
};