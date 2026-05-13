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
        // 0. Truncate tables to avoid FK constraint errors later when recreating FKs on empty 'variantes' table
        \Illuminate\Support\Facades\DB::table('carrito')->truncate();
        \Illuminate\Support\Facades\DB::table('detalle_pedido')->truncate();

        // 1. Drop FKs and unique constraints that depend on variantes_producto
        try {
            Schema::table('carrito', function (Blueprint $table) {
                $table->dropForeign(['id_variante']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('detalle_pedido', function (Blueprint $table) {
                $table->dropForeign(['id_variante']);
            });
        } catch (\Exception $e) {}

        // 2. Drop the old table
        Schema::dropIfExists('variantes_producto');

        // 3. Add precio_base to productos
        Schema::table('productos', function (Blueprint $table) {
            $table->decimal('precio_base', 10, 2)->default(0)->after('brand');
        });

        // 4. Create grupos_opcion_producto
        Schema::create('grupos_opcion_producto', function (Blueprint $table) {
            $table->id('id_grupo');
            $table->unsignedBigInteger('id_producto');
            $table->string('nombre', 50); // e.g. Color, Almacenamiento
            $table->string('tipo', 20)->default('texto'); // e.g. color, texto
            $table->integer('orden')->default(0);
            $table->timestamps();

            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
        });

        // 5. Create valores_opcion_producto
        Schema::create('valores_opcion_producto', function (Blueprint $table) {
            $table->id('id_valor');
            $table->unsignedBigInteger('id_grupo');
            $table->string('nombre', 50); // e.g. Sand Storm, 512GB
            $table->string('hex_code', 10)->nullable(); // only for type color
            $table->string('imagen', 255)->nullable(); // only for type color
            $table->decimal('precio_extra', 10, 2)->default(0);
            $table->integer('orden')->default(0);
            $table->timestamps();

            $table->foreign('id_grupo')->references('id_grupo')->on('grupos_opcion_producto')->onDelete('cascade');
        });

        // 6. Create variantes (new clean table)
        Schema::create('variantes', function (Blueprint $table) {
            $table->id('id_variante');
            $table->unsignedBigInteger('id_producto');
            $table->decimal('precio', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('sku', 50)->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
        });

        // 7. Create variante_valores
        Schema::create('variante_valores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_variante');
            $table->unsignedBigInteger('id_valor');

            $table->foreign('id_variante')->references('id_variante')->on('variantes')->onDelete('cascade');
            $table->foreign('id_valor')->references('id_valor')->on('valores_opcion_producto')->onDelete('cascade');

            $table->unique(['id_variante', 'id_valor']);
        });

        // 8. Re-add FKs to carrito and detalle_pedido
        Schema::table('carrito', function (Blueprint $table) {
            $table->foreign('id_variante')->references('id_variante')->on('variantes')->onDelete('cascade');
        });

        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->foreign('id_variante')->references('id_variante')->on('variantes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carrito', function (Blueprint $table) {
            $table->dropForeign(['id_variante']);
        });

        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropForeign(['id_variante']);
        });

        Schema::dropIfExists('variante_valores');
        Schema::dropIfExists('variantes');
        Schema::dropIfExists('valores_opcion_producto');
        Schema::dropIfExists('grupos_opcion_producto');

        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('precio_base');
        });

        Schema::create('variantes_producto', function (Blueprint $table) {
            $table->id('id_variante');
            $table->unsignedBigInteger('id_producto');
            $table->string('color', 50);
            $table->string('almacenamiento', 50);
            $table->decimal('precio', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('imagen', 255)->nullable();
            $table->string('sku', 50)->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
        });

        Schema::table('carrito', function (Blueprint $table) {
            $table->foreign('id_variante')->references('id_variante')->on('variantes_producto')->onDelete('cascade');
        });

        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->foreign('id_variante')->references('id_variante')->on('variantes_producto')->onDelete('cascade');
        });
    }
};
