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
        Schema::table('imagen_productos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_valor')->nullable()->after('id_producto');
            $table->foreign('id_valor')
                  ->references('id_valor')
                  ->on('valores_opcion_producto')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('imagen_productos', function (Blueprint $table) {
            $table->dropForeign(['id_valor']);
            $table->dropColumn('id_valor');
        });
    }
};
