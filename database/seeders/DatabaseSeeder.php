<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\DetallePedido;
use App\Models\GrupoOpcionProducto;
use App\Models\ImagenProducto;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use App\Models\ValorOpcionProducto;
use App\Models\Variante;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedAll();
        });
    }

    private function seedAll(): void
    {
        // 1. Categorías
        $catSmartphones = Categoria::create(['nombre' => 'Smartphones'])->id_categoria;
        $catOrdenadores = Categoria::create(['nombre' => 'Ordenadores'])->id_categoria;
        $catTablets     = Categoria::create(['nombre' => 'Tablets'])->id_categoria;
        $catAccesorios  = Categoria::create(['nombre' => 'Accesorios'])->id_categoria;
        $catHogar       = Categoria::create(['nombre' => 'Hogar'])->id_categoria;

        // 2. Usuarios
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@synapse.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->rol = 'admin';
        $admin->save();

        $cliente = User::create([
            'name'     => 'Juan Pérez',
            'email'    => 'juan@correo.com',
            'password' => Hash::make('password123'),
        ]);
        $cliente->rol = 'cliente';
        $cliente->save();

        // 3. Productos — $variants[] collects one representative variant per product for orders
        $variants = [];

        // ─── SMARTPHONES (10) ─────────────────────────────────────────────

        // OnePlus 15
        $p = Producto::create(['nombre' => 'OnePlus 15', 'brand' => 'OnePlus', 'descripcion' => 'El último flagship con cámara Hasselblad y rendimiento extremo.', 'id_categoria' => $catSmartphones, 'precio_base' => 949.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Sand Storm',     '#dcbfa8', 1);
        $v2 = $this->colorVal($cG, 'Infinite Black', '#000000', 2);
        $v3 = $this->colorVal($cG, 'Ultra Violet',   '#7f00ff', 3);
        $s1 = $this->almVal($aG, '12 GB RAM + 256 GB ROM', 0,  1);
        $s2 = $this->almVal($aG, '16 GB RAM + 512 GB ROM', 80, 2);
        $variants[] = $this->variant($p->id_producto, 1029.00, 50, 'OP15-SAND-512', [$v1, $s2]);
        $this->variant($p->id_producto, 1029.00, 30, 'OP15-BLK-512', [$v2, $s2]);
        $this->variant($p->id_producto,  949.00,  0, 'OP15-VIO-256', [$v3, $s1]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/Oneplus15.png',      'orden' => 1]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/ImagenOnePlus1.jpg', 'orden' => 2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/ImagenOnePlus2.png', 'orden' => 3]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/ImagenOnePlus3.png', 'orden' => 4]);

        // iPhone 17 Pro Max
        $p = Producto::create(['nombre' => 'iPhone 17 Pro Max', 'brand' => 'Apple', 'descripcion' => 'El mejor iPhone creado hasta la fecha con Titanio y A19 Bionic.', 'id_categoria' => $catSmartphones, 'precio_base' => 1700.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Natural Titanium', '#d4c5b0', 1);
        $v2 = $this->colorVal($cG, 'Black Titanium',   '#1a1a1a', 2);
        $s1 = $this->almVal($aG, '12 GB RAM + 256 GB ROM', 0,   1);
        $s2 = $this->almVal($aG, '12 GB RAM + 512 GB ROM', 130, 2);
        $variants[] = $this->variant($p->id_producto, 1700.00, 15, 'IP17PM-NAT-256', [$v1, $s1]);
        $this->variant($p->id_producto, 1830.00,  8, 'IP17PM-BLK-512', [$v2, $s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/iPhone17ProMax.png', 'orden' => 1]);

        // Oppo Find X9 Pro
        $p = Producto::create(['nombre' => 'Oppo Find X9 Pro', 'brand' => 'Oppo', 'descripcion' => 'Innovación y diseño con carga ultrarrápida SuperVOOC.', 'id_categoria' => $catSmartphones, 'precio_base' => 1299.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Pearl White', '#f0ebe6', 1);
        $s1 = $this->almVal($aG, '16 GB RAM + 512 GB ROM', 0, 1);
        $variants[] = $this->variant($p->id_producto, 1299.00, 10, 'OPPO-X9P-WHT-512', [$v1, $s1]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/OppoFindX9Pro.png', 'orden' => 1]);

        // iPhone 17 Air
        $p = Producto::create(['nombre' => 'iPhone 17 Air', 'brand' => 'Apple', 'descripcion' => 'El diseño más fino y ligero de Apple, potencia en tus manos.', 'id_categoria' => $catSmartphones, 'precio_base' => 1050.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Sky Blue', '#87ceeb', 1);
        $v2 = $this->colorVal($cG, 'White',    '#f5f5f5', 2);
        $s1 = $this->almVal($aG, '8 GB RAM + 256 GB ROM', 0,   1);
        $s2 = $this->almVal($aG, '8 GB RAM + 512 GB ROM', 130, 2);
        $variants[] = $this->variant($p->id_producto, 1050.00, 25, 'IP17A-BLU-256', [$v1, $s1]);
        $this->variant($p->id_producto, 1180.00, 10, 'IP17A-WHT-512', [$v2, $s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/iphone17AirBlue.png', 'orden' => 1]);

        // Nothing Phone (1)
        $p = Producto::create(['nombre' => 'Nothing Phone (1)', 'brand' => 'Nothing', 'descripcion' => 'Diseño transparente con la interfaz Glyph única en su clase.', 'id_categoria' => $catSmartphones, 'precio_base' => 499.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'White', '#f5f5f5', 1);
        $v2 = $this->colorVal($cG, 'Black', '#1a1a1a', 2);
        $s1 = $this->almVal($aG, '8 GB RAM + 256 GB ROM', 0, 1);
        $variants[] = $this->variant($p->id_producto, 499.00, 12, 'NP1-WHT-256', [$v1, $s1]);
        $this->variant($p->id_producto, 499.00, 20, 'NP1-BLK-256', [$v2, $s1]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/nothingPhone1.png', 'orden' => 1]);

        // Samsung Galaxy S25 Ultra
        $p = Producto::create(['nombre' => 'Samsung Galaxy S25 Ultra', 'brand' => 'Samsung', 'descripcion' => 'El tope de gama de Samsung con S-Pen integrado y cámara de 200MP.', 'id_categoria' => $catSmartphones, 'precio_base' => 1459.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Titanium Gray',  '#6b6b6b', 1);
        $v2 = $this->colorVal($cG, 'Phantom Black',  '#1a1a1a', 2);
        $s1 = $this->almVal($aG, '12 GB RAM + 256 GB ROM', 0,   1);
        $s2 = $this->almVal($aG, '12 GB RAM + 512 GB ROM', 120, 2);
        $variants[] = $this->variant($p->id_producto, 1459.00, 20, 'S25U-GRY-256', [$v1, $s1]);
        $this->variant($p->id_producto, 1579.00, 15, 'S25U-BLK-512', [$v2, $s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/samsungS25Ultra.png', 'orden' => 1]);

        // Google Pixel 10 Pro
        $p = Producto::create(['nombre' => 'Google Pixel 10 Pro', 'brand' => 'Google', 'descripcion' => 'La mejor experiencia de Android puro con inteligencia artificial avanzada.', 'id_categoria' => $catSmartphones, 'precio_base' => 1099.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Obsidian',  '#2d2d2d', 1);
        $v2 = $this->colorVal($cG, 'Porcelain', '#f0ece4', 2);
        $s1 = $this->almVal($aG, '12 GB RAM + 128 GB ROM', 0,   1);
        $s2 = $this->almVal($aG, '12 GB RAM + 256 GB ROM', 100, 2);
        $variants[] = $this->variant($p->id_producto, 1099.00, 25, 'GXP10-OBS-128', [$v1, $s1]);
        $this->variant($p->id_producto, 1199.00, 10, 'GXP10-POR-256', [$v2, $s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/pixel10Pro.png', 'orden' => 1]);

        // Xiaomi 15 Pro
        $p = Producto::create(['nombre' => 'Xiaomi 15 Pro', 'brand' => 'Xiaomi', 'descripcion' => 'Rendimiento excepcional con óptica Leica y carga ultrarrápida de 90W.', 'id_categoria' => $catSmartphones, 'precio_base' => 999.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Classic Black', '#1a1a1a', 1);
        $s1 = $this->almVal($aG, '12 GB RAM + 256 GB ROM', 0, 1);
        $variants[] = $this->variant($p->id_producto, 999.00, 30, 'XI15P-BLK-256', [$v1, $s1]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/xiaomi15Pro.png', 'orden' => 1]);

        // iPhone 17
        $p = Producto::create(['nombre' => 'iPhone 17', 'brand' => 'Apple', 'descripcion' => 'El equilibrio perfecto entre potencia y diseño de Apple.', 'id_categoria' => $catSmartphones, 'precio_base' => 959.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Midnight',  '#1c1c1c', 1);
        $v2 = $this->colorVal($cG, 'Starlight', '#f5f5f0', 2);
        $s1 = $this->almVal($aG, '128 GB ROM', 0,   1);
        $s2 = $this->almVal($aG, '256 GB ROM', 130, 2);
        $variants[] = $this->variant($p->id_producto, 959.00,  100, 'IP17-MID-128', [$v1, $s1]);
        $this->variant($p->id_producto, 1089.00, 80, 'IP17-STA-256', [$v2, $s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/iPhone17.png', 'orden' => 1]);

        // Samsung Galaxy Z Fold 7
        $p = Producto::create(['nombre' => 'Samsung Galaxy Z Fold 7', 'brand' => 'Samsung', 'descripcion' => 'El plegable definitivo que redefine la productividad móvil.', 'id_categoria' => $catSmartphones, 'precio_base' => 1899.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Navy Blue', '#002366', 1);
        $s1 = $this->almVal($aG, '12 GB RAM + 512 GB ROM', 0, 1);
        $variants[] = $this->variant($p->id_producto, 1899.00, 10, 'SZFD7-NVY-512', [$v1, $s1]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/galaxyZFold7.png', 'orden' => 1]);

        // ─── ORDENADORES (5) ──────────────────────────────────────────────

        // MacBook Air M4
        $p = Producto::create(['nombre' => 'MacBook Air M4', 'brand' => 'Apple', 'descripcion' => 'El portátil más vendido del mundo, ahora con el chip M4.', 'id_categoria' => $catOrdenadores, 'precio_base' => 1299.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Space Gray', '#6d6d6d', 1);
        $v2 = $this->colorVal($cG, 'Midnight',   '#1c1c1c', 2);
        $v3 = $this->colorVal($cG, 'Starlight',  '#f5f5f0', 3);
        $s1 = $this->almVal($aG, '16 GB RAM + 256 GB SSD', 0,   1);
        $s2 = $this->almVal($aG, '16 GB RAM + 512 GB SSD', 200, 2);
        $variants[] = $this->variant($p->id_producto, 1299.00, 30, 'MBA-M4-SGR-256', [$v1, $s1]);
        $this->variant($p->id_producto, 1499.00, 20, 'MBA-M4-SGR-512', [$v1, $s2]);
        $this->variant($p->id_producto, 1299.00, 15, 'MBA-M4-MID-256', [$v2, $s1]);
        $this->variant($p->id_producto, 1299.00, 10, 'MBA-M4-STA-256', [$v3, $s1]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/macbookAir.png', 'orden' => 1]);

        // Dell XPS 15
        $p = Producto::create(['nombre' => 'Dell XPS 15', 'brand' => 'Dell', 'descripcion' => 'Pantalla OLED 4K y procesador Intel Core Ultra para profesionales exigentes.', 'id_categoria' => $catOrdenadores, 'precio_base' => 1799.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Platinum Silver', '#c0c0c0', 1);
        $v2 = $this->colorVal($cG, 'Graphite Black',  '#333333', 2);
        $s1 = $this->almVal($aG, '16 GB RAM + 512 GB SSD', 0,   1);
        $s2 = $this->almVal($aG, '32 GB RAM + 1 TB SSD',   500, 2);
        $variants[] = $this->variant($p->id_producto, 1799.00, 12, 'DELL-XPS15-PLT-512', [$v1, $s1]);
        $this->variant($p->id_producto, 2299.00,  8, 'DELL-XPS15-GPH-1TB', [$v2, $s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/dellXPS15.png', 'orden' => 1]);

        // HP Spectre x360 14
        $p = Producto::create(['nombre' => 'HP Spectre x360 14', 'brand' => 'HP', 'descripcion' => 'Convertible 2-en-1 premium con pantalla OLED táctil y diseño joya.', 'id_categoria' => $catOrdenadores, 'precio_base' => 1599.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Nightfall Black', '#1a1a1a', 1);
        $v2 = $this->colorVal($cG, 'Natural Silver',  '#e0e0e0', 2);
        $s1 = $this->almVal($aG, '16 GB RAM + 512 GB SSD', 0, 1);
        $variants[] = $this->variant($p->id_producto, 1599.00, 18, 'HP-SPX360-NF-512', [$v1, $s1]);
        $this->variant($p->id_producto, 1599.00, 10, 'HP-SPX360-NS-512', [$v2, $s1]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/hpSpectreX360.png', 'orden' => 1]);

        // Lenovo ThinkPad X1 Carbon
        $p = Producto::create(['nombre' => 'Lenovo ThinkPad X1 Carbon', 'brand' => 'Lenovo', 'descripcion' => 'El ultrabook empresarial más ligero con teclado legendario ThinkPad.', 'id_categoria' => $catOrdenadores, 'precio_base' => 1499.00]);
        $aG = GrupoOpcionProducto::create(['id_producto' => $p->id_producto, 'nombre' => 'Almacenamiento', 'tipo' => 'texto', 'orden' => 1]);
        $s1 = $this->almVal($aG, '16 GB RAM + 512 GB SSD', 0,   1);
        $s2 = $this->almVal($aG, '32 GB RAM + 1 TB SSD',   400, 2);
        $variants[] = $this->variant($p->id_producto, 1499.00, 20, 'LNVO-X1C-512', [$s1]);
        $this->variant($p->id_producto, 1899.00, 10, 'LNVO-X1C-1TB', [$s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/lenovoX1Carbon.png', 'orden' => 1]);

        // Microsoft Surface Pro 11
        $p = Producto::create(['nombre' => 'Microsoft Surface Pro 11', 'brand' => 'Microsoft', 'descripcion' => 'Versatilidad total con Copilot+ integrado y Snapdragon X Elite.', 'id_categoria' => $catOrdenadores, 'precio_base' => 1299.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Platinum', '#e0e0e0', 1);
        $v2 = $this->colorVal($cG, 'Sapphire', '#0f4c81', 2);
        $s1 = $this->almVal($aG, '16 GB RAM + 256 GB SSD', 0,   1);
        $s2 = $this->almVal($aG, '16 GB RAM + 512 GB SSD', 200, 2);
        $variants[] = $this->variant($p->id_producto, 1299.00, 15, 'MSSP11-PLT-256', [$v1, $s1]);
        $this->variant($p->id_producto, 1499.00,  8, 'MSSP11-SAP-512', [$v2, $s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/surfacePro11.png', 'orden' => 1]);

        // ─── TABLETS (4) ──────────────────────────────────────────────────

        // iPad Pro M4
        $p = Producto::create(['nombre' => 'iPad Pro M4', 'brand' => 'Apple', 'descripcion' => 'Potencia desmesurada con el chip M4 y pantalla OLED.', 'id_categoria' => $catTablets, 'precio_base' => 1299.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Space Black', '#1c1c1c', 1);
        $v2 = $this->colorVal($cG, 'Silver',      '#c0c0c0', 2);
        $s1 = $this->almVal($aG, '8 GB RAM + 512 GB ROM',  0,   1);
        $s2 = $this->almVal($aG, '16 GB RAM + 1 TB ROM',   600, 2);
        $variants[] = $this->variant($p->id_producto, 1299.00, 20, 'IPAD-PRO-M4-SB-512', [$v1, $s1]);
        $this->variant($p->id_producto, 1899.00,  5, 'IPAD-PRO-M4-SB-1TB', [$v1, $s2]);
        $this->variant($p->id_producto, 1299.00, 15, 'IPAD-PRO-M4-SV-512', [$v2, $s1]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/ipadPro.png', 'orden' => 1]);

        // Samsung Galaxy Tab S10 Ultra
        $p = Producto::create(['nombre' => 'Samsung Galaxy Tab S10 Ultra', 'brand' => 'Samsung', 'descripcion' => 'Pantalla Dynamic AMOLED 2X de 14,6" para máxima productividad.', 'id_categoria' => $catTablets, 'precio_base' => 1199.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Graphite', '#333333', 1);
        $s1 = $this->almVal($aG, '12 GB RAM + 256 GB ROM', 0,   1);
        $s2 = $this->almVal($aG, '12 GB RAM + 512 GB ROM', 200, 2);
        $variants[] = $this->variant($p->id_producto, 1199.00, 18, 'TABS10U-GR-256', [$v1, $s1]);
        $this->variant($p->id_producto, 1399.00, 10, 'TABS10U-GR-512', [$v1, $s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/galaxyTabS10Ultra.png', 'orden' => 1]);

        // Microsoft Surface Go 4
        $p = Producto::create(['nombre' => 'Microsoft Surface Go 4', 'brand' => 'Microsoft', 'descripcion' => 'La tablet Windows más compacta y ligera, ideal para estudiantes y profesionales.', 'id_categoria' => $catTablets, 'precio_base' => 679.00]);
        $aG = GrupoOpcionProducto::create(['id_producto' => $p->id_producto, 'nombre' => 'Almacenamiento', 'tipo' => 'texto', 'orden' => 1]);
        $s1 = $this->almVal($aG, '8 GB RAM + 64 GB eMMC',  0,   1);
        $s2 = $this->almVal($aG, '8 GB RAM + 128 GB SSD', 100, 2);
        $variants[] = $this->variant($p->id_producto, 679.00, 25, 'MSGO4-64',  [$s1]);
        $this->variant($p->id_producto, 779.00, 15, 'MSGO4-128', [$s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/surfaceGo4.png', 'orden' => 1]);

        // Xiaomi Pad 7 Pro
        $p = Producto::create(['nombre' => 'Xiaomi Pad 7 Pro', 'brand' => 'Xiaomi', 'descripcion' => 'Pantalla LCD 144Hz y Snapdragon 8s Gen 3 a un precio imbatible.', 'id_categoria' => $catTablets, 'precio_base' => 499.00]);
        [$cG, $aG] = $this->phoneGroups($p->id_producto);
        $v1 = $this->colorVal($cG, 'Mist Black',   '#2d2d2d', 1);
        $v2 = $this->colorVal($cG, 'Glacier Gray', '#9e9e9e', 2);
        $s1 = $this->almVal($aG, '8 GB RAM + 128 GB ROM', 0,  1);
        $s2 = $this->almVal($aG, '8 GB RAM + 256 GB ROM', 80, 2);
        $variants[] = $this->variant($p->id_producto, 499.00, 40, 'XIPAD7-BLK-128', [$v1, $s1]);
        $this->variant($p->id_producto, 579.00, 20, 'XIPAD7-GRY-256', [$v2, $s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/xiaomiPad7Pro.png', 'orden' => 1]);

        // ─── ACCESORIOS (3) ───────────────────────────────────────────────

        // Google Pixel Watch 4
        $p = Producto::create(['nombre' => 'Google Pixel Watch 4', 'brand' => 'Google', 'descripcion' => 'El smartwatch más inteligente con integración Fitbit premium.', 'id_categoria' => $catAccesorios, 'precio_base' => 349.00]);
        $cG = GrupoOpcionProducto::create(['id_producto' => $p->id_producto, 'nombre' => 'Color', 'tipo' => 'color', 'orden' => 1]);
        $v1 = $this->colorVal($cG, 'Obsidian Black', '#1a1a1a', 1);
        $v2 = $this->colorVal($cG, 'Porcelain',      '#f0ece4', 2);
        $variants[] = $this->variant($p->id_producto, 349.00, 45, 'PW4-OBS', [$v1]);
        $this->variant($p->id_producto, 349.00, 30, 'PW4-POR', [$v2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/pixelWatch4.png', 'orden' => 1]);

        // Apple Watch Series 10
        $p = Producto::create(['nombre' => 'Apple Watch Series 10', 'brand' => 'Apple', 'descripcion' => 'El reloj Apple más fino con pantalla OLED más brillante y detección de apnea del sueño.', 'id_categoria' => $catAccesorios, 'precio_base' => 399.00]);
        $cG = GrupoOpcionProducto::create(['id_producto' => $p->id_producto, 'nombre' => 'Color', 'tipo' => 'color', 'orden' => 1]);
        $tG = GrupoOpcionProducto::create(['id_producto' => $p->id_producto, 'nombre' => 'Talla', 'tipo' => 'texto', 'orden' => 2]);
        $v1 = $this->colorVal($cG, 'Midnight',  '#1c1c1c', 1);
        $v2 = $this->colorVal($cG, 'Starlight', '#f5f5f0', 2);
        $s1 = $this->almVal($tG, '42 mm', 0,  1);
        $s2 = $this->almVal($tG, '46 mm', 30, 2);
        $variants[] = $this->variant($p->id_producto, 399.00, 40, 'AWS10-MID-42', [$v1, $s1]);
        $this->variant($p->id_producto, 429.00, 30, 'AWS10-MID-46', [$v1, $s2]);
        $this->variant($p->id_producto, 399.00, 35, 'AWS10-STA-42', [$v2, $s1]);
        $this->variant($p->id_producto, 429.00, 20, 'AWS10-STA-46', [$v2, $s2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/appleWatchS10.png', 'orden' => 1]);

        // Sony WH-1000XM6
        $p = Producto::create(['nombre' => 'Sony WH-1000XM6', 'brand' => 'Sony', 'descripcion' => 'Los mejores auriculares con cancelación de ruido activa del mercado, ahora más ligeros.', 'id_categoria' => $catAccesorios, 'precio_base' => 379.00]);
        $cG = GrupoOpcionProducto::create(['id_producto' => $p->id_producto, 'nombre' => 'Color', 'tipo' => 'color', 'orden' => 1]);
        $v1 = $this->colorVal($cG, 'Midnight Black',  '#1a1a1a', 1);
        $v2 = $this->colorVal($cG, 'Platinum Silver', '#d0d0d0', 2);
        $variants[] = $this->variant($p->id_producto, 379.00, 50, 'SNYWH6-BLK', [$v1]);
        $this->variant($p->id_producto, 379.00, 30, 'SNYWH6-SLV', [$v2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/sonyWH1000XM6.png', 'orden' => 1]);

        // ─── HOGAR (3) ────────────────────────────────────────────────────

        // Cafetera Smart Xiaomi
        $p = Producto::create(['nombre' => 'Cafetera Smart Xiaomi', 'brand' => 'Xiaomi', 'descripcion' => 'Cafetera inteligente con control por app, preparación programable y molinillo integrado.', 'id_categoria' => $catHogar, 'precio_base' => 99.00]);
        $cG = GrupoOpcionProducto::create(['id_producto' => $p->id_producto, 'nombre' => 'Color', 'tipo' => 'color', 'orden' => 1]);
        $v1 = $this->colorVal($cG, 'White', '#f5f5f5', 1);
        $v2 = $this->colorVal($cG, 'Black', '#1a1a1a', 2);
        $variants[] = $this->variant($p->id_producto, 99.00, 80, 'CAF-XMI-WHT', [$v1]);
        $this->variant($p->id_producto, 99.00, 60, 'CAF-XMI-BLK', [$v2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/cafeteraXiaomi.png', 'orden' => 1]);

        // Amazon Echo Pop
        $p = Producto::create(['nombre' => 'Amazon Echo Pop', 'brand' => 'Amazon', 'descripcion' => 'Altavoz inteligente compacto con Alexa para controlar tu hogar desde cualquier rincón.', 'id_categoria' => $catHogar, 'precio_base' => 49.00]);
        $cG = GrupoOpcionProducto::create(['id_producto' => $p->id_producto, 'nombre' => 'Color', 'tipo' => 'color', 'orden' => 1]);
        $v1 = $this->colorVal($cG, 'Charcoal',      '#36454f', 1);
        $v2 = $this->colorVal($cG, 'Glacier White', '#f5f5f5', 2);
        $v3 = $this->colorVal($cG, 'Lavender',      '#e6e6fa', 3);
        $variants[] = $this->variant($p->id_producto, 49.00, 120, 'ECHO-POP-CHA', [$v1]);
        $this->variant($p->id_producto, 49.00,  90, 'ECHO-POP-WHT', [$v2]);
        $this->variant($p->id_producto, 49.00,  70, 'ECHO-POP-LAV', [$v3]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/echoPop.png', 'orden' => 1]);

        // Roborock S8 Pro Ultra
        $p = Producto::create(['nombre' => 'Roborock S8 Pro Ultra', 'brand' => 'Roborock', 'descripcion' => 'Robot aspirador y friegasuelos con mopa retráctil y vaciado automático en base.', 'id_categoria' => $catHogar, 'precio_base' => 799.00]);
        $cG = GrupoOpcionProducto::create(['id_producto' => $p->id_producto, 'nombre' => 'Color', 'tipo' => 'color', 'orden' => 1]);
        $v1 = $this->colorVal($cG, 'White', '#f5f5f5', 1);
        $v2 = $this->colorVal($cG, 'Black', '#1a1a1a', 2);
        $variants[] = $this->variant($p->id_producto, 799.00, 30, 'RRK-S8P-WHT', [$v1]);
        $this->variant($p->id_producto, 799.00, 20, 'RRK-S8P-BLK', [$v2]);
        ImagenProducto::create(['id_producto' => $p->id_producto, 'ruta' => 'assets/roborockS8Pro.png', 'orden' => 1]);

        // 4. Pedidos
        $this->seedOrders($cliente->id, $admin->id, $variants);
    }

    /**
     * $variants indices used in orders:
     *  [0]  OnePlus 15 — Sand Storm 512 — 1029.00
     *  [6]  Google Pixel 10 Pro — Obsidian 128 — 1099.00
     *  [10] MacBook Air M4 — Space Gray 256 — 1299.00
     *  [19] Google Pixel Watch 4 — Obsidian — 349.00
     *  [15] iPad Pro M4 — Space Black 512 — 1299.00
     *  [22] Cafetera Smart Xiaomi — White — 99.00
     */
    private function seedOrders(int $clienteId, int $adminId, array $variants): void
    {
        // Cliente — pedido 1 (entregado, hace 30 días)
        $p1 = Pedido::create([
            'id_usuario'    => $clienteId,
            'fecha'         => now()->subDays(30),
            'total'         => 0,
            'estado'        => 'entregado',
            'nombre_envio'  => 'Juan Pérez',
            'direccion'     => 'Calle Mayor 12, 3º A',
            'ciudad'        => 'Madrid',
            'codigo_postal' => '28001',
            'provincia'     => 'Madrid',
            'telefono'      => '612345678',
        ]);
        $d1 = DetallePedido::create(['id_pedido' => $p1->id_pedido, 'id_variante' => $variants[0]->id_variante, 'cantidad' => 1, 'precio_unitario' => $variants[0]->precio]);
        $d2 = DetallePedido::create(['id_pedido' => $p1->id_pedido, 'id_variante' => $variants[6]->id_variante, 'cantidad' => 1, 'precio_unitario' => $variants[6]->precio]);
        $p1->update(['total' => $d1->precio_unitario + $d2->precio_unitario]);

        // Cliente — pedido 2 (enviado, hace 10 días)
        $p2 = Pedido::create([
            'id_usuario'    => $clienteId,
            'fecha'         => now()->subDays(10),
            'total'         => 0,
            'estado'        => 'enviado',
            'nombre_envio'  => 'Juan Pérez',
            'direccion'     => 'Calle Mayor 12, 3º A',
            'ciudad'        => 'Madrid',
            'codigo_postal' => '28001',
            'provincia'     => 'Madrid',
            'telefono'      => '612345678',
        ]);
        $d3 = DetallePedido::create(['id_pedido' => $p2->id_pedido, 'id_variante' => $variants[10]->id_variante, 'cantidad' => 1, 'precio_unitario' => $variants[10]->precio]);
        $d4 = DetallePedido::create(['id_pedido' => $p2->id_pedido, 'id_variante' => $variants[19]->id_variante, 'cantidad' => 2, 'precio_unitario' => $variants[19]->precio]);
        $p2->update(['total' => $d3->precio_unitario + ($d4->precio_unitario * 2)]);

        // Cliente — pedido 3 (pendiente, hace 2 días)
        $p3 = Pedido::create([
            'id_usuario'    => $clienteId,
            'fecha'         => now()->subDays(2),
            'total'         => 0,
            'estado'        => 'pendiente',
            'nombre_envio'  => 'Juan Pérez',
            'direccion'     => 'Calle Mayor 12, 3º A',
            'ciudad'        => 'Madrid',
            'codigo_postal' => '28001',
            'provincia'     => 'Madrid',
            'telefono'      => '612345678',
        ]);
        $d5 = DetallePedido::create(['id_pedido' => $p3->id_pedido, 'id_variante' => $variants[15]->id_variante, 'cantidad' => 1, 'precio_unitario' => $variants[15]->precio]);
        $p3->update(['total' => $d5->precio_unitario]);

        // Admin — pedido 1 (pagado, hace 5 días)
        $p4 = Pedido::create([
            'id_usuario'    => $adminId,
            'fecha'         => now()->subDays(5),
            'total'         => 0,
            'estado'        => 'pagado',
            'nombre_envio'  => 'Admin User',
            'direccion'     => 'Avenida de la Tecnología 99',
            'ciudad'        => 'Barcelona',
            'codigo_postal' => '08001',
            'provincia'     => 'Barcelona',
            'telefono'      => '698765432',
        ]);
        $d6 = DetallePedido::create(['id_pedido' => $p4->id_pedido, 'id_variante' => $variants[22]->id_variante, 'cantidad' => 1, 'precio_unitario' => $variants[22]->precio]);
        $p4->update(['total' => $d6->precio_unitario]);
    }

    private function phoneGroups(int $productoId): array
    {
        $colorGrp = GrupoOpcionProducto::create([
            'id_producto' => $productoId,
            'nombre'      => 'Color',
            'tipo'        => 'color',
            'orden'       => 1,
        ]);

        $almGrp = GrupoOpcionProducto::create([
            'id_producto' => $productoId,
            'nombre'      => 'Almacenamiento',
            'tipo'        => 'texto',
            'orden'       => 2,
        ]);

        return [$colorGrp, $almGrp];
    }

    private function colorVal(GrupoOpcionProducto $grupo, string $nombre, string $hex, int $orden): int
    {
        return ValorOpcionProducto::create([
            'id_grupo'     => $grupo->id_grupo,
            'nombre'       => $nombre,
            'hex_code'     => $hex,
            'precio_extra' => 0,
            'orden'        => $orden,
        ])->id_valor;
    }

    private function almVal(GrupoOpcionProducto $grupo, string $nombre, float $extra, int $orden): int
    {
        return ValorOpcionProducto::create([
            'id_grupo'     => $grupo->id_grupo,
            'nombre'       => $nombre,
            'precio_extra' => $extra,
            'orden'        => $orden,
        ])->id_valor;
    }

    private function variant(int $productoId, float $precio, int $stock, string $sku, array $valorIds): Variante
    {
        $variante = Variante::create([
            'id_producto' => $productoId,
            'precio'      => $precio,
            'stock'       => $stock,
            'sku'         => $sku,
        ]);

        $variante->valores()->attach($valorIds);

        return $variante;
    }
}
