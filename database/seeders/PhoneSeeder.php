<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\GrupoOpcionProducto;
use App\Models\Producto;
use App\Models\ValorOpcionProducto;
use App\Models\Variante;
use Illuminate\Database\Seeder;

class PhoneSeeder extends Seeder
{
    public function run(): void
    {
        $categoriaId = Categoria::where('nombre', 'Smartphones')->first()->id_categoria;

        $phones = [
            [
                'nombre'      => 'Samsung Galaxy S25 Ultra',
                'brand'       => 'Samsung',
                'descripcion' => 'El tope de gama de Samsung con S-Pen integrado y cámara de 200MP.',
                'precio_base' => 1459.00,
                'colores'     => [
                    ['nombre' => 'Titanium Gray', 'hex' => '#6b6b6b'],
                    ['nombre' => 'Phantom Black', 'hex' => '#1a1a1a'],
                ],
                'almacenamientos' => [
                    ['nombre' => '12 GB RAM + 256 GB ROM', 'extra' => 0],
                    ['nombre' => '12 GB RAM + 512 GB ROM', 'extra' => 120],
                ],
                'variantes' => [
                    ['color' => 'Titanium Gray', 'alm' => '12 GB RAM + 256 GB ROM', 'precio' => 1459.00, 'stock' => 20, 'sku' => 'S25U-GRY-256'],
                    ['color' => 'Phantom Black', 'alm' => '12 GB RAM + 512 GB ROM', 'precio' => 1579.00, 'stock' => 15, 'sku' => 'S25U-BLK-512'],
                ],
            ],
            [
                'nombre'      => 'Google Pixel 10 Pro',
                'brand'       => 'Google',
                'descripcion' => 'La mejor experiencia de Android puro con inteligencia artificial avanzada.',
                'precio_base' => 1099.00,
                'colores'     => [
                    ['nombre' => 'Obsidian', 'hex' => '#2d2d2d'],
                    ['nombre' => 'Porcelain', 'hex' => '#f0ece4'],
                ],
                'almacenamientos' => [
                    ['nombre' => '12 GB RAM + 128 GB ROM', 'extra' => 0],
                    ['nombre' => '12 GB RAM + 256 GB ROM', 'extra' => 100],
                ],
                'variantes' => [
                    ['color' => 'Obsidian',  'alm' => '12 GB RAM + 128 GB ROM', 'precio' => 1099.00, 'stock' => 25, 'sku' => 'GXP10-OBS-128'],
                    ['color' => 'Porcelain', 'alm' => '12 GB RAM + 256 GB ROM', 'precio' => 1199.00, 'stock' => 10, 'sku' => 'GXP10-POR-256'],
                ],
            ],
            [
                'nombre'      => 'Xiaomi 15 Pro',
                'brand'       => 'Xiaomi',
                'descripcion' => 'Rendimiento excepcional con óptica Leica y carga ultra rápida.',
                'precio_base' => 999.00,
                'colores'     => [['nombre' => 'Classic Black', 'hex' => '#1a1a1a']],
                'almacenamientos' => [['nombre' => '12 GB RAM + 256 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Classic Black', 'alm' => '12 GB RAM + 256 GB ROM', 'precio' => 999.00, 'stock' => 30, 'sku' => 'XI15P-BLK-256'],
                ],
            ],
            [
                'nombre'      => 'Motorola Edge 60 Ultra',
                'brand'       => 'Motorola',
                'descripcion' => 'Diseño elegante con pantalla curva y experiencia de software fluida.',
                'precio_base' => 899.00,
                'colores'     => [['nombre' => 'Interstellar Black', 'hex' => '#0d0d0d']],
                'almacenamientos' => [['nombre' => '12 GB RAM + 512 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Interstellar Black', 'alm' => '12 GB RAM + 512 GB ROM', 'precio' => 899.00, 'stock' => 15, 'sku' => 'MOTE60-BLK-512'],
                ],
            ],
            [
                'nombre'      => 'Sony Xperia 1 VII',
                'brand'       => 'Sony',
                'descripcion' => 'Pantalla 4K HDR OLED y funciones de cámara profesional.',
                'precio_base' => 1399.00,
                'colores'     => [['nombre' => 'Black', 'hex' => '#1a1a1a']],
                'almacenamientos' => [['nombre' => '12 GB RAM + 256 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Black', 'alm' => '12 GB RAM + 256 GB ROM', 'precio' => 1399.00, 'stock' => 8, 'sku' => 'SNYX1-BLK-256'],
                ],
            ],
            [
                'nombre'      => 'Asus Zenfone 12',
                'brand'       => 'Asus',
                'descripcion' => 'El mejor smartphone compacto con potencia de flagship.',
                'precio_base' => 799.00,
                'colores'     => [['nombre' => 'Midnight Blue', 'hex' => '#003087']],
                'almacenamientos' => [['nombre' => '8 GB RAM + 256 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Midnight Blue', 'alm' => '8 GB RAM + 256 GB ROM', 'precio' => 799.00, 'stock' => 20, 'sku' => 'ASUZ12-BLU-256'],
                ],
            ],
            [
                'nombre'      => 'Realme GT 7 Pro',
                'brand'       => 'Realme',
                'descripcion' => 'Velocidad pura y carga de 240W para los más exigentes.',
                'precio_base' => 749.00,
                'colores'     => [['nombre' => 'Mars Orange', 'hex' => '#e8611a']],
                'almacenamientos' => [['nombre' => '16 GB RAM + 512 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Mars Orange', 'alm' => '16 GB RAM + 512 GB ROM', 'precio' => 749.00, 'stock' => 40, 'sku' => 'REGT7-ORG-512'],
                ],
            ],
            [
                'nombre'      => 'Vivo X110 Pro+',
                'brand'       => 'Vivo',
                'descripcion' => 'Fotografía de retrato líder en la industria con sensor de 1 pulgada.',
                'precio_base' => 1199.00,
                'colores'     => [['nombre' => 'Legendary Black', 'hex' => '#1a1a1a']],
                'almacenamientos' => [['nombre' => '12 GB RAM + 512 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Legendary Black', 'alm' => '12 GB RAM + 512 GB ROM', 'precio' => 1199.00, 'stock' => 12, 'sku' => 'VIX110-BLK-512'],
                ],
            ],
            [
                'nombre'      => 'Honor Magic 7 Pro',
                'brand'       => 'Honor',
                'descripcion' => 'Pantalla ultra resistente y batería de larga duración.',
                'precio_base' => 1049.00,
                'colores'     => [['nombre' => 'Cyan', 'hex' => '#00bcd4']],
                'almacenamientos' => [['nombre' => '12 GB RAM + 512 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Cyan', 'alm' => '12 GB RAM + 512 GB ROM', 'precio' => 1049.00, 'stock' => 18, 'sku' => 'HONM7-CYN-512'],
                ],
            ],
            [
                'nombre'      => 'Nothing Phone (3)',
                'brand'       => 'Nothing',
                'descripcion' => 'Evolución del diseño icónico con mejoras en rendimiento y cámara.',
                'precio_base' => 699.00,
                'colores'     => [
                    ['nombre' => 'Dark Grey', 'hex' => '#4a4a4a'],
                    ['nombre' => 'White',     'hex' => '#f5f5f5'],
                ],
                'almacenamientos' => [['nombre' => '12 GB RAM + 256 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Dark Grey', 'alm' => '12 GB RAM + 256 GB ROM', 'precio' => 699.00, 'stock' => 50, 'sku' => 'NP3-GRY-256'],
                    ['color' => 'White',     'alm' => '12 GB RAM + 256 GB ROM', 'precio' => 699.00, 'stock' => 35, 'sku' => 'NP3-WHT-256'],
                ],
            ],
            [
                'nombre'      => 'Huawei P70 Art',
                'brand'       => 'Huawei',
                'descripcion' => 'Diseño artístico único con capacidades fotográficas revolucionarias.',
                'precio_base' => 1599.00,
                'colores'     => [['nombre' => 'Blue Ocean', 'hex' => '#0077b6']],
                'almacenamientos' => [['nombre' => '16 GB RAM + 1 TB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Blue Ocean', 'alm' => '16 GB RAM + 1 TB ROM', 'precio' => 1599.00, 'stock' => 5, 'sku' => 'HWP70-BLU-1TB'],
                ],
            ],
            [
                'nombre'      => 'Samsung Galaxy Z Fold 7',
                'brand'       => 'Samsung',
                'descripcion' => 'El plegable definitivo que redefine la productividad móvil.',
                'precio_base' => 1899.00,
                'colores'     => [['nombre' => 'Navy Blue', 'hex' => '#002366']],
                'almacenamientos' => [['nombre' => '12 GB RAM + 512 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Navy Blue', 'alm' => '12 GB RAM + 512 GB ROM', 'precio' => 1899.00, 'stock' => 10, 'sku' => 'SZFD7-NVY-512'],
                ],
            ],
            [
                'nombre'      => 'iPhone 17',
                'brand'       => 'Apple',
                'descripcion' => 'El equilibrio perfecto entre potencia y diseño.',
                'precio_base' => 959.00,
                'colores'     => [
                    ['nombre' => 'Midnight',  'hex' => '#1c1c1c'],
                    ['nombre' => 'Starlight', 'hex' => '#f5f5f0'],
                ],
                'almacenamientos' => [
                    ['nombre' => '128 GB ROM', 'extra' => 0],
                    ['nombre' => '256 GB ROM', 'extra' => 130],
                ],
                'variantes'   => [
                    ['color' => 'Midnight',  'alm' => '128 GB ROM', 'precio' => 959.00,  'stock' => 100, 'sku' => 'IP17-MID-128'],
                    ['color' => 'Starlight', 'alm' => '256 GB ROM', 'precio' => 1089.00, 'stock' => 80,  'sku' => 'IP17-STA-256'],
                ],
            ],
            [
                'nombre'      => 'Poco F7 Pro',
                'brand'       => 'Xiaomi',
                'descripcion' => 'Potencia bruta al mejor precio para gamers.',
                'precio_base' => 549.00,
                'colores'     => [['nombre' => 'Electric Blue', 'hex' => '#0047ab']],
                'almacenamientos' => [['nombre' => '12 GB RAM + 256 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Electric Blue', 'alm' => '12 GB RAM + 256 GB ROM', 'precio' => 549.00, 'stock' => 60, 'sku' => 'POF7-BLU-256'],
                ],
            ],
            [
                'nombre'      => 'Redmagic 10 Pro',
                'brand'       => 'ZTE',
                'descripcion' => 'Smartphone gaming con ventilador activo y gatillos táctiles.',
                'precio_base' => 849.00,
                'colores'     => [['nombre' => 'Void Black', 'hex' => '#0a0a0a']],
                'almacenamientos' => [['nombre' => '16 GB RAM + 512 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Void Black', 'alm' => '16 GB RAM + 512 GB ROM', 'precio' => 849.00, 'stock' => 25, 'sku' => 'RM10-BLK-512'],
                ],
            ],
            [
                'nombre'      => 'Oppo Reno 13 Pro',
                'brand'       => 'Oppo',
                'descripcion' => 'Experto en retratos con un diseño ultra delgado.',
                'precio_base' => 649.00,
                'colores'     => [['nombre' => 'Glossy Gold', 'hex' => '#d4af37']],
                'almacenamientos' => [['nombre' => '12 GB RAM + 256 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Glossy Gold', 'alm' => '12 GB RAM + 256 GB ROM', 'precio' => 649.00, 'stock' => 30, 'sku' => 'OPR13-GLD-256'],
                ],
            ],
            [
                'nombre'      => 'Google Pixel 10a',
                'brand'       => 'Google',
                'descripcion' => 'La magia del Pixel a un precio más accesible.',
                'precio_base' => 549.00,
                'colores'     => [['nombre' => 'Charcoal', 'hex' => '#36454f']],
                'almacenamientos' => [['nombre' => '8 GB RAM + 128 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Charcoal', 'alm' => '8 GB RAM + 128 GB ROM', 'precio' => 549.00, 'stock' => 150, 'sku' => 'GXP10A-CHA-128'],
                ],
            ],
            [
                'nombre'      => 'Fairphone 6',
                'brand'       => 'Fairphone',
                'descripcion' => 'El smartphone más sostenible y reparable del mundo.',
                'precio_base' => 749.00,
                'colores'     => [['nombre' => 'Matte Black', 'hex' => '#1c1c1c']],
                'almacenamientos' => [['nombre' => '8 GB RAM + 256 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Matte Black', 'alm' => '8 GB RAM + 256 GB ROM', 'precio' => 749.00, 'stock' => 15, 'sku' => 'FP6-BLK-256'],
                ],
            ],
            [
                'nombre'      => 'OnePlus Nord 5',
                'brand'       => 'OnePlus',
                'descripcion' => 'Todo lo que necesitas, exactamente lo que quieres.',
                'precio_base' => 499.00,
                'colores'     => [['nombre' => 'Jade Green', 'hex' => '#00a86b']],
                'almacenamientos' => [['nombre' => '12 GB RAM + 256 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Jade Green', 'alm' => '12 GB RAM + 256 GB ROM', 'precio' => 499.00, 'stock' => 45, 'sku' => 'OPN5-GRN-256'],
                ],
            ],
            [
                'nombre'      => 'Nokia XR30',
                'brand'       => 'Nokia',
                'descripcion' => 'Diseñado para durar, resistente a caídas y agua.',
                'precio_base' => 449.00,
                'colores'     => [['nombre' => 'Forest Green', 'hex' => '#228b22']],
                'almacenamientos' => [['nombre' => '6 GB RAM + 128 GB ROM', 'extra' => 0]],
                'variantes'   => [
                    ['color' => 'Forest Green', 'alm' => '6 GB RAM + 128 GB ROM', 'precio' => 449.00, 'stock' => 20, 'sku' => 'NKXR30-GRN-128'],
                ],
            ],
        ];

        foreach ($phones as $phoneData) {
            $producto = Producto::create([
                'nombre'       => $phoneData['nombre'],
                'brand'        => $phoneData['brand'],
                'descripcion'  => $phoneData['descripcion'],
                'id_categoria' => $categoriaId,
                'precio_base'  => $phoneData['precio_base'],
            ]);

            $colorGroup = GrupoOpcionProducto::create([
                'id_producto' => $producto->id_producto,
                'nombre'      => 'Color',
                'tipo'        => 'color',
                'orden'       => 1,
            ]);

            $storageGroup = GrupoOpcionProducto::create([
                'id_producto' => $producto->id_producto,
                'nombre'      => 'Almacenamiento',
                'tipo'        => 'texto',
                'orden'       => 2,
            ]);

            $colorMap = [];
            foreach ($phoneData['colores'] as $i => $color) {
                $valor = ValorOpcionProducto::create([
                    'id_grupo'    => $colorGroup->id_grupo,
                    'nombre'      => $color['nombre'],
                    'hex_code'    => $color['hex'],
                    'precio_extra' => 0,
                    'orden'       => $i + 1,
                ]);
                $colorMap[$color['nombre']] = $valor->id_valor;
            }

            $storageMap = [];
            foreach ($phoneData['almacenamientos'] as $i => $alm) {
                $valor = ValorOpcionProducto::create([
                    'id_grupo'    => $storageGroup->id_grupo,
                    'nombre'      => $alm['nombre'],
                    'precio_extra' => $alm['extra'],
                    'orden'       => $i + 1,
                ]);
                $storageMap[$alm['nombre']] = $valor->id_valor;
            }

            foreach ($phoneData['variantes'] as $variantData) {
                $variante = Variante::create([
                    'id_producto' => $producto->id_producto,
                    'precio'      => $variantData['precio'],
                    'stock'       => $variantData['stock'],
                    'sku'         => $variantData['sku'],
                ]);

                $variante->valores()->attach([
                    $colorMap[$variantData['color']],
                    $storageMap[$variantData['alm']],
                ]);
            }
        }
    }
}
