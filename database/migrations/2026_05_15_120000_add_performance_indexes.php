<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Index plan — fields hit by catalog filter/sort/search paths.
     * Each block is wrapped in try/catch so a partial re-run is idempotent
     * (some FKs may already have an implicit index under MySQL InnoDB).
     */
    public function up(): void
    {
        $this->safeIndex('productos', ['nombre'], 'productos_nombre_index');
        $this->safeIndex('productos', ['brand'], 'productos_brand_index');
        $this->safeIndex('productos', ['precio_base'], 'productos_precio_base_index');
        $this->safeIndex('productos', ['id_categoria'], 'productos_id_categoria_index');

        $this->safeIndex('variantes', ['precio'], 'variantes_precio_index');
        $this->safeIndex('variantes', ['stock'], 'variantes_stock_index');
        $this->safeIndex('variantes', ['id_producto'], 'variantes_id_producto_index');

        $this->safeIndex('grupos_opcion_producto', ['id_producto'], 'grupos_opcion_id_producto_index');
        $this->safeIndex('valores_opcion_producto', ['id_grupo'], 'valores_opcion_id_grupo_index');

        $this->safeIndex('carrito', ['id_usuario'], 'carrito_id_usuario_index');

        $this->safeIndex('pedidos', ['id_usuario'], 'pedidos_id_usuario_index');
        $this->safeIndex('pedidos', ['fecha'], 'pedidos_fecha_index');
        $this->safeIndex('pedidos', ['estado'], 'pedidos_estado_index');

        $this->safeIndex('detalle_pedido', ['id_pedido'], 'detalle_pedido_id_pedido_index');
        $this->safeIndex('detalle_pedido', ['id_variante'], 'detalle_pedido_id_variante_index');

        $this->safeIndex('imagenes_producto', ['id_producto'], 'imagenes_producto_id_producto_index');
        $this->safeIndex('imagenes_producto', ['id_valor'], 'imagenes_producto_id_valor_index');
    }

    public function down(): void
    {
        $names = [
            'productos' => ['productos_nombre_index', 'productos_brand_index', 'productos_precio_base_index', 'productos_id_categoria_index'],
            'variantes' => ['variantes_precio_index', 'variantes_stock_index', 'variantes_id_producto_index'],
            'grupos_opcion_producto' => ['grupos_opcion_id_producto_index'],
            'valores_opcion_producto' => ['valores_opcion_id_grupo_index'],
            'carrito' => ['carrito_id_usuario_index'],
            'pedidos' => ['pedidos_id_usuario_index', 'pedidos_fecha_index', 'pedidos_estado_index'],
            'detalle_pedido' => ['detalle_pedido_id_pedido_index', 'detalle_pedido_id_variante_index'],
            'imagenes_producto' => ['imagenes_producto_id_producto_index', 'imagenes_producto_id_valor_index'],
        ];

        foreach ($names as $table => $indexes) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            foreach ($indexes as $name) {
                try {
                    Schema::table($table, fn (Blueprint $t) => $t->dropIndex($name));
                } catch (\Throwable $e) {
                    // swallow — index may not exist
                }
            }
        }
    }

    protected function safeIndex(string $table, array $columns, string $name): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }
        foreach ($columns as $col) {
            if (!Schema::hasColumn($table, $col)) {
                return;
            }
        }
        try {
            Schema::table($table, fn (Blueprint $t) => $t->index($columns, $name));
        } catch (\Throwable $e) {
            // index already exists (e.g. implicit FK index) — ignore
        }
    }
};
