<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variantes_producto', function (Blueprint $table) {
            $table->id('id_variante');
            $table->unsignedBigInteger('id_producto');
            $table->string('color', 50);
            $table->string('almacenamiento', 50);
            $table->decimal('precio', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('imagen', 255)->nullable();
            $table->string('sku', 50)->nullable()->unique();
            
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
            $table->unique(['id_producto', 'color', 'almacenamiento']);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variantes_producto');
    }
};
