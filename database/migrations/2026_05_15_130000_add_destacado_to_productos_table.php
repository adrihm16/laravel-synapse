<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->boolean('destacado')->default(false)->after('precio_base');
            $table->index('destacado', 'productos_destacado_index');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            try {
                $table->dropIndex('productos_destacado_index');
            } catch (\Throwable $e) {}
            $table->dropColumn('destacado');
        });
    }
};
