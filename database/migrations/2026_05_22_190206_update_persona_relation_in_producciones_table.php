<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('producciones', function (Blueprint $table) {

            if (!Schema::hasColumn('producciones', 'persona_id')) {

                $table->foreignId('persona_id')
                      ->nullable()
                      ->constrained('personas')
                      ->nullOnDelete();

            }

        });
    }

    public function down(): void
    {
        Schema::table('producciones', function (Blueprint $table) {

            if (Schema::hasColumn('producciones', 'persona_id')) {

                $table->dropForeign(['persona_id']);
                $table->dropColumn('persona_id');

            }

        });
    }
};