<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('nombre_envio', 150)->nullable()->after('estado');
            $table->string('direccion', 255)->nullable()->after('nombre_envio');
            $table->string('ciudad', 100)->nullable()->after('direccion');
            $table->string('codigo_postal', 10)->nullable()->after('ciudad');
            $table->string('provincia', 100)->nullable()->after('codigo_postal');
            $table->string('telefono', 20)->nullable()->after('provincia');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_envio',
                'direccion',
                'ciudad',
                'codigo_postal',
                'provincia',
                'telefono',
            ]);
        });
    }
};
