<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movimientos_materiales', function (Blueprint $table) {

            if(!Schema::hasColumn('movimientos_materiales','user_id'))
            {
                $table->foreignId('user_id')
                      ->nullable()
                      ->constrained()
                      ->nullOnDelete();
            }

        });

        Schema::table('movimientos_prendas', function (Blueprint $table) {

            if(!Schema::hasColumn('movimientos_prendas','user_id'))
            {
                $table->foreignId('user_id')
                      ->nullable()
                      ->constrained()
                      ->nullOnDelete();
            }

        });

        Schema::table('producciones', function (Blueprint $table) {

            if(!Schema::hasColumn('producciones','user_id'))
            {
                $table->foreignId('user_id')
                      ->nullable()
                      ->constrained()
                      ->nullOnDelete();
            }

        });
    }

    public function down(): void
    {
        Schema::table('movimientos_materiales', function (Blueprint $table) {

            if(Schema::hasColumn('movimientos_materiales','user_id'))
            {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

        });

        Schema::table('movimientos_prendas', function (Blueprint $table) {

            if(Schema::hasColumn('movimientos_prendas','user_id'))
            {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

        });

        Schema::table('producciones', function (Blueprint $table) {

            if(Schema::hasColumn('producciones','user_id'))
            {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

        });
    }
};