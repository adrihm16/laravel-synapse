<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\GrupoOpcionProducto;
use App\Models\ImagenProducto;
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
        $categorias = ['Smartphones', 'Ordenadores', 'Tablets', 'Accesorios', 'Hogar'];
        foreach ($categorias as $cat) {
            Categoria::create(['nombre' => $cat]);
        }

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

        $catSmartphones = Categoria::where('nombre', 'Smartphones')->first()->id_categoria;
        $catOrdenadores = Categoria::where('nombre', 'Ordenadores')->first()->id_categoria;
        $catTablets     = Categoria::where('nombre', 'Tablets')->first()->id_categoria;
        $catAccesorios  = Categoria::where('nombre', 'Accesorios')->first()->id_categoria;
        $catHogar       = Categoria::where('nombre', 'Hogar')->first()->id_categoria;

        // --- SMARTPHONES ---

        // OnePlus 15
        $op15 = Producto::create([
            'nombre'       => 'One Plus 15',
            'brand'        => 'OnePlus',
            'descripcion'  => 'El último flagship con cámara Hasselblad y rendimiento extremo.',
            'id_categoria' => $catSmartphones,
            'precio_base'  => 949.00,
        ]);
        [$cGrp, $aGrp] = $this->phoneGroups($op15->id_producto);
        $sandStorm   = $this->colorVal($cGrp, 'Sand Storm',     '#dcbfa8', 1);
        $infBlack    = $this->colorVal($cGrp, 'Infinite Black', '#000000', 2);
        $ultraViolet = $this->colorVal($cGrp, 'Ultra Violet',   '#7f00ff', 3);
        $alm256      = $this->almVal($aGrp, '12 GB RAM + 256 GB ROM', 0,  1);
        $alm512      = $this->almVal($aGrp, '16 GB RAM + 512 GB ROM', 80, 2);
        $this->variant($op15->id_producto, 1029.00, 50, 'OP15-SAND-512', [$sandStorm,   $alm512]);
        $this->variant($op15->id_producto, 1029.00, 30, 'OP15-BLK-512',  [$infBlack,    $alm512]);
        $this->variant($op15->id_producto,  949.00,  0, 'OP15-VIO-256',  [$ultraViolet, $alm256]);
        ImagenProducto::create(['id_producto' => $op15->id_producto, 'ruta' => 'assets/Oneplus15.png',      'orden' => 1]);
        ImagenProducto::create(['id_producto' => $op15->id_producto, 'ruta' => 'assets/ImagenOnePlus1.jpg', 'orden' => 2]);
        ImagenProducto::create(['id_producto' => $op15->id_producto, 'ruta' => 'assets/ImagenOnePlus2.png', 'orden' => 3]);
        ImagenProducto::create(['id_producto' => $op15->id_producto, 'ruta' => 'assets/ImagenOnePlus3.png', 'orden' => 4]);

        // iPhone 17 Pro Max
        $ip17pm = Producto::create([
            'nombre'       => 'iPhone 17 Pro Max',
            'brand'        => 'Apple',
            'descripcion'  => 'El mejor iPhone creado hasta la fecha con Titanium y A19 Bionic.',
            'id_categoria' => $catSmartphones,
            'precio_base'  => 1700.00,
        ]);
        [$cGrp, $aGrp] = $this->phoneGroups($ip17pm->id_producto);
        $natTit = $this->colorVal($cGrp, 'Natural Titanium', '#d4c5b0', 1);
        $black  = $this->colorVal($cGrp, 'Black Titanium',   '#1a1a1a', 2);
        $alm256 = $this->almVal($aGrp, '12 GB RAM + 256 GB ROM', 0,   1);
        $alm512 = $this->almVal($aGrp, '12 GB RAM + 512 GB ROM', 130, 2);
        $this->variant($ip17pm->id_producto, 1700.00, 15, 'IP17PM-NAT-256', [$natTit, $alm256]);
        $this->variant($ip17pm->id_producto, 1830.00,  8, 'IP17PM-BLK-512', [$black,  $alm512]);
        ImagenProducto::create(['id_producto' => $ip17pm->id_producto, 'ruta' => 'assets/iPhone17ProMax.png', 'orden' => 1]);

        // Oppo Find X9 Pro
        $oppo = Producto::create([
            'nombre'       => 'Oppo Find X9 Pro',
            'brand'        => 'Oppo',
            'descripcion'  => 'Innovación y diseño con carga ultrarrápida SuperVOOC.',
            'id_categoria' => $catSmartphones,
            'precio_base'  => 1299.00,
        ]);
        [$cGrp, $aGrp] = $this->phoneGroups($oppo->id_producto);
        $pearlWhite = $this->colorVal($cGrp, 'Pearl White', '#f0ebe6', 1);
        $alm512     = $this->almVal($aGrp, '16 GB RAM + 512 GB ROM', 0, 1);
        $this->variant($oppo->id_producto, 1299.00, 10, 'OPPO-X9P-WHT-512', [$pearlWhite, $alm512]);
        ImagenProducto::create(['id_producto' => $oppo->id_producto, 'ruta' => 'assets/OppoFindX9Pro.png', 'orden' => 1]);

        // iPhone 17 Air
        $ip17air = Producto::create([
            'nombre'       => 'iPhone 17 Air',
            'brand'        => 'Apple',
            'descripcion'  => 'El diseño más fino y ligero de Apple, potencia en tus manos.',
            'id_categoria' => $catSmartphones,
            'precio_base'  => 1050.00,
        ]);
        [$cGrp, $aGrp] = $this->phoneGroups($ip17air->id_producto);
        $skyBlue = $this->colorVal($cGrp, 'Sky Blue', '#87ceeb', 1);
        $white   = $this->colorVal($cGrp, 'White',    '#f5f5f5', 2);
        $alm256  = $this->almVal($aGrp, '8 GB RAM + 256 GB ROM', 0,   1);
        $alm512  = $this->almVal($aGrp, '8 GB RAM + 512 GB ROM', 130, 2);
        $this->variant($ip17air->id_producto, 1050.00, 25, 'IP17A-BLU-256', [$skyBlue, $alm256]);
        $this->variant($ip17air->id_producto, 1180.00, 10, 'IP17A-WHT-512', [$white,   $alm512]);
        ImagenProducto::create(['id_producto' => $ip17air->id_producto, 'ruta' => 'assets/iphone17AirBlue.png', 'orden' => 1]);

        // Nothing Phone (1)
        $np1 = Producto::create([
            'nombre'       => 'Nothing Phone (1)',
            'brand'        => 'Nothing',
            'descripcion'  => 'Diseño transparente con la interfaz Glyph única en su clase.',
            'id_categoria' => $catSmartphones,
            'precio_base'  => 499.00,
        ]);
        [$cGrp, $aGrp] = $this->phoneGroups($np1->id_producto);
        $white  = $this->colorVal($cGrp, 'White', '#f5f5f5', 1);
        $black  = $this->colorVal($cGrp, 'Black', '#1a1a1a', 2);
        $alm256 = $this->almVal($aGrp, '8 GB RAM + 256 GB ROM', 0, 1);
        $this->variant($np1->id_producto, 499.00, 12, 'NP1-WHT-256', [$white, $alm256]);
        $this->variant($np1->id_producto, 499.00, 20, 'NP1-BLK-256', [$black, $alm256]);
        ImagenProducto::create(['id_producto' => $np1->id_producto, 'ruta' => 'assets/nothingPhone1.png', 'orden' => 1]);

        // --- ORDENADORES ---

        // MacBook Air
        $mac = Producto::create([
            'nombre'       => 'MacBook Air M4',
            'brand'        => 'Apple',
            'descripcion'  => 'El portátil más vendido del mundo, ahora con el chip M4.',
            'id_categoria' => $catOrdenadores,
            'precio_base'  => 1299.00,
        ]);
        [$cGrp, $aGrp] = $this->phoneGroups($mac->id_producto);
        $spaceGray = $this->colorVal($cGrp, 'Space Gray', '#6d6d6d', 1);
        $midnight  = $this->colorVal($cGrp, 'Midnight',   '#1c1c1c', 2);
        $starlight = $this->colorVal($cGrp, 'Starlight',  '#f5f5f0', 3);
        $alm256    = $this->almVal($aGrp, '16 GB RAM + 256 GB SSD', 0,   1);
        $alm512    = $this->almVal($aGrp, '16 GB RAM + 512 GB SSD', 200, 2);
        $this->variant($mac->id_producto, 1299.00, 30, 'MBA-M4-SGR-256', [$spaceGray, $alm256]);
        $this->variant($mac->id_producto, 1499.00, 20, 'MBA-M4-SGR-512', [$spaceGray, $alm512]);
        $this->variant($mac->id_producto, 1299.00, 15, 'MBA-M4-MID-256', [$midnight,  $alm256]);
        $this->variant($mac->id_producto, 1299.00, 10, 'MBA-M4-STA-256', [$starlight, $alm256]);
        ImagenProducto::create(['id_producto' => $mac->id_producto, 'ruta' => 'assets/macbookAir.png', 'orden' => 1]);

        // --- TABLETS ---

        // iPad Pro M4
        $ipad = Producto::create([
            'nombre'       => 'iPad Pro M4',
            'brand'        => 'Apple',
            'descripcion'  => 'Potencia desmesurada con el chip M4 y pantalla OLED.',
            'id_categoria' => $catTablets,
            'precio_base'  => 1299.00,
        ]);
        [$cGrp, $aGrp] = $this->phoneGroups($ipad->id_producto);
        $spaceBlack = $this->colorVal($cGrp, 'Space Black', '#1c1c1c', 1);
        $silver     = $this->colorVal($cGrp, 'Silver',      '#c0c0c0', 2);
        $alm512     = $this->almVal($aGrp, '8 GB RAM + 512 GB ROM',  0,   1);
        $alm1tb     = $this->almVal($aGrp, '16 GB RAM + 1 TB ROM',   600, 2);
        $this->variant($ipad->id_producto, 1299.00,  20, 'IPAD-PRO-M4-SB-512', [$spaceBlack, $alm512]);
        $this->variant($ipad->id_producto, 1899.00,   5, 'IPAD-PRO-M4-SB-1TB', [$spaceBlack, $alm1tb]);
        $this->variant($ipad->id_producto, 1299.00,  15, 'IPAD-PRO-M4-SV-512', [$silver,     $alm512]);
        ImagenProducto::create(['id_producto' => $ipad->id_producto, 'ruta' => 'assets/ipadPro.png', 'orden' => 1]);

        // --- ACCESORIOS ---

        // Google Pixel Watch 4 (Color only — no storage concept)
        $pw4 = Producto::create([
            'nombre'       => 'Google Pixel Watch 4',
            'brand'        => 'Google',
            'descripcion'  => 'El smartwatch más inteligente con integración Fitbit premium.',
            'id_categoria' => $catAccesorios,
            'precio_base'  => 349.00,
        ]);
        $cGrp5 = GrupoOpcionProducto::create([
            'id_producto' => $pw4->id_producto,
            'nombre'      => 'Color',
            'tipo'        => 'color',
            'orden'       => 1,
        ]);
        $obsBlack  = $this->colorVal($cGrp5, 'Obsidian Black', '#1a1a1a', 1);
        $porcelain = $this->colorVal($cGrp5, 'Porcelain',      '#f0ece4', 2);
        $this->variant($pw4->id_producto, 349.00, 45, 'PW4-OBS', [$obsBlack]);
        $this->variant($pw4->id_producto, 349.00, 30, 'PW4-POR', [$porcelain]);
        ImagenProducto::create(['id_producto' => $pw4->id_producto, 'ruta' => 'assets/pixelWatch4.png', 'orden' => 1]);

        // --- HOGAR ---

        // Cafetera Xiaomi
        $cafetera = Producto::create([
            'nombre'       => 'Cafetera Smart Xiaomi',
            'brand'        => 'Xiaomi',
            'descripcion'  => 'Cafetera inteligente con control por app, preparación programable y molinillo integrado.',
            'id_categoria' => $catHogar,
            'precio_base'  => 99.00,
        ]);
        $cGrp8 = GrupoOpcionProducto::create([
            'id_producto' => $cafetera->id_producto,
            'nombre'      => 'Color',
            'tipo'        => 'color',
            'orden'       => 1,
        ]);
        $white8 = $this->colorVal($cGrp8, 'White', '#f5f5f5', 1);
        $black8 = $this->colorVal($cGrp8, 'Black', '#1a1a1a', 2);
        $this->variant($cafetera->id_producto, 99.00, 80, 'CAF-XMI-WHT', [$white8]);
        $this->variant($cafetera->id_producto, 99.00, 60, 'CAF-XMI-BLK', [$black8]);
        ImagenProducto::create(['id_producto' => $cafetera->id_producto, 'ruta' => 'assets/cafeteraXiaomi.png', 'orden' => 1]);
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
            'id_grupo'    => $grupo->id_grupo,
            'nombre'      => $nombre,
            'hex_code'    => $hex,
            'precio_extra' => 0,
            'orden'       => $orden,
        ])->id_valor;
    }

    private function almVal(GrupoOpcionProducto $grupo, string $nombre, float $extra, int $orden): int
    {
        return ValorOpcionProducto::create([
            'id_grupo'    => $grupo->id_grupo,
            'nombre'      => $nombre,
            'precio_extra' => $extra,
            'orden'       => $orden,
        ])->id_valor;
    }

    private function variant(int $productoId, float $precio, int $stock, string $sku, array $valorIds): void
    {
        $variante = Variante::create([
            'id_producto' => $productoId,
            'precio'      => $precio,
            'stock'       => $stock,
            'sku'         => $sku,
        ]);

        $variante->valores()->attach($valorIds);
    }
}
