<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\VarianteProducto;
use Illuminate\Database\Seeder;

class PhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoriaId = Categoria::where('nombre', 'Smartphones')->first()->id_categoria;

        $phones = [
            [
                'nombre' => 'Samsung Galaxy S25 Ultra',
                'brand' => 'Samsung',
                'descripcion' => 'El tope de gama de Samsung con S-Pen integrado y cámara de 200MP.',
                'variantes' => [
                    ['color' => 'Titanium Gray', 'almacenamiento' => '12 GB RAM + 256 GB ROM', 'precio' => 1459.00, 'stock' => 20, 'sku' => 'S25U-GRY-256'],
                    ['color' => 'Phantom Black', 'almacenamiento' => '12 GB RAM + 512 GB ROM', 'precio' => 1579.00, 'stock' => 15, 'sku' => 'S25U-BLK-512'],
                ]
            ],
            [
                'nombre' => 'Google Pixel 10 Pro',
                'brand' => 'Google',
                'descripcion' => 'La mejor experiencia de Android puro con inteligencia artificial avanzada.',
                'variantes' => [
                    ['color' => 'Obsidian', 'almacenamiento' => '12 GB RAM + 128 GB ROM', 'precio' => 1099.00, 'stock' => 25, 'sku' => 'GXP10-OBS-128'],
                    ['color' => 'Porcelain', 'almacenamiento' => '12 GB RAM + 256 GB ROM', 'precio' => 1199.00, 'stock' => 10, 'sku' => 'GXP10-POR-256'],
                ]
            ],
            [
                'nombre' => 'Xiaomi 15 Pro',
                'brand' => 'Xiaomi',
                'descripcion' => 'Rendimiento excepcional con óptica Leica y carga ultra rápida.',
                'variantes' => [
                    ['color' => 'Classic Black', 'almacenamiento' => '12 GB RAM + 256 GB ROM', 'precio' => 999.00, 'stock' => 30, 'sku' => 'XI15P-BLK-256'],
                ]
            ],
            [
                'nombre' => 'Motorola Edge 60 Ultra',
                'brand' => 'Motorola',
                'descripcion' => 'Diseño elegante con pantalla curva y experiencia de software fluida.',
                'variantes' => [
                    ['color' => 'Interstellar Black', 'almacenamiento' => '12 GB RAM + 512 GB ROM', 'precio' => 899.00, 'stock' => 15, 'sku' => 'MOTE60-BLK-512'],
                ]
            ],
            [
                'nombre' => 'Sony Xperia 1 VII',
                'brand' => 'Sony',
                'descripcion' => 'Pantalla 4K HDR OLED y funciones de cámara profesional.',
                'variantes' => [
                    ['color' => 'Black', 'almacenamiento' => '12 GB RAM + 256 GB ROM', 'precio' => 1399.00, 'stock' => 8, 'sku' => 'SNYX1-BLK-256'],
                ]
            ],
            [
                'nombre' => 'Asus Zenfone 12',
                'brand' => 'Asus',
                'descripcion' => 'El mejor smartphone compacto con potencia de flagship.',
                'variantes' => [
                    ['color' => 'Midnight Blue', 'almacenamiento' => '8 GB RAM + 256 GB ROM', 'precio' => 799.00, 'stock' => 20, 'sku' => 'ASUZ12-BLU-256'],
                ]
            ],
            [
                'nombre' => 'Realme GT 7 Pro',
                'brand' => 'Realme',
                'descripcion' => 'Velocidad pura y carga de 240W para los más exigentes.',
                'variantes' => [
                    ['color' => 'Mars Orange', 'almacenamiento' => '16 GB RAM + 512 GB ROM', 'precio' => 749.00, 'stock' => 40, 'sku' => 'REGT7-ORG-512'],
                ]
            ],
            [
                'nombre' => 'Vivo X110 Pro+',
                'brand' => 'Vivo',
                'descripcion' => 'Fotografía de retrato líder en la industria con sensor de 1 pulgada.',
                'variantes' => [
                    ['color' => 'Legendary Black', 'almacenamiento' => '12 GB RAM + 512 GB ROM', 'precio' => 1199.00, 'stock' => 12, 'sku' => 'VIX110-BLK-512'],
                ]
            ],
            [
                'nombre' => 'Honor Magic 7 Pro',
                'brand' => 'Honor',
                'descripcion' => 'Pantalla ultra resistente y batería de larga duración.',
                'variantes' => [
                    ['color' => 'Cyan', 'almacenamiento' => '12 GB RAM + 512 GB ROM', 'precio' => 1049.00, 'stock' => 18, 'sku' => 'HONM7-CYN-512'],
                ]
            ],
            [
                'nombre' => 'Nothing Phone (3)',
                'brand' => 'Nothing',
                'descripcion' => 'Evolución del diseño icónico con mejoras en rendimiento y cámara.',
                'variantes' => [
                    ['color' => 'Dark Grey', 'almacenamiento' => '12 GB RAM + 256 GB ROM', 'precio' => 699.00, 'stock' => 50, 'sku' => 'NP3-GRY-256'],
                    ['color' => 'White', 'almacenamiento' => '12 GB RAM + 256 GB ROM', 'precio' => 699.00, 'stock' => 35, 'sku' => 'NP3-WHT-256'],
                ]
            ],
            [
                'nombre' => 'Huawei P70 Art',
                'brand' => 'Huawei',
                'descripcion' => 'Diseño artístico único con capacidades fotográficas revolucionarias.',
                'variantes' => [
                    ['color' => 'Blue Ocean', 'almacenamiento' => '16 GB RAM + 1 TB ROM', 'precio' => 1599.00, 'stock' => 5, 'sku' => 'HWP70-BLU-1TB'],
                ]
            ],
            [
                'nombre' => 'Samsung Galaxy Z Fold 7',
                'brand' => 'Samsung',
                'descripcion' => 'El plegable definitivo que redefine la productividad móvil.',
                'variantes' => [
                    ['color' => 'Navy Blue', 'almacenamiento' => '12 GB RAM + 512 GB ROM', 'precio' => 1899.00, 'stock' => 10, 'sku' => 'SZFD7-NVY-512'],
                ]
            ],
            [
                'nombre' => 'iPhone 17',
                'brand' => 'Apple',
                'descripcion' => 'El equilibrio perfecto entre potencia y diseño.',
                'variantes' => [
                    ['color' => 'Midnight', 'almacenamiento' => '128 GB ROM', 'precio' => 959.00, 'stock' => 100, 'sku' => 'IP17-MID-128'],
                    ['color' => 'Starlight', 'almacenamiento' => '256 GB ROM', 'precio' => 1089.00, 'stock' => 80, 'sku' => 'IP17-STA-256'],
                ]
            ],
            [
                'nombre' => 'Poco F7 Pro',
                'brand' => 'Xiaomi',
                'descripcion' => 'Potencia bruta al mejor precio para gamers.',
                'variantes' => [
                    ['color' => 'Electric Blue', 'almacenamiento' => '12 GB RAM + 256 GB ROM', 'precio' => 549.00, 'stock' => 60, 'sku' => 'POF7-BLU-256'],
                ]
            ],
            [
                'nombre' => 'Redmagic 10 Pro',
                'brand' => 'ZTE',
                'descripcion' => 'Smartphone gaming con ventilador activo y gatillos táctiles.',
                'variantes' => [
                    ['color' => 'Void Black', 'almacenamiento' => '16 GB RAM + 512 GB ROM', 'precio' => 849.00, 'stock' => 25, 'sku' => 'RM10-BLK-512'],
                ]
            ],
            [
                'nombre' => 'Oppo Reno 13 Pro',
                'brand' => 'Oppo',
                'descripcion' => 'Experto en retratos con un diseño ultra delgado.',
                'variantes' => [
                    ['color' => 'Glossy Gold', 'almacenamiento' => '12 GB RAM + 256 GB ROM', 'precio' => 649.00, 'stock' => 30, 'sku' => 'OPR13-GLD-256'],
                ]
            ],
            [
                'nombre' => 'Google Pixel 10a',
                'brand' => 'Google',
                'descripcion' => 'La magia del Pixel a un precio más accesible.',
                'variantes' => [
                    ['color' => 'Charcoal', 'almacenamiento' => '8 GB RAM + 128 GB ROM', 'precio' => 549.00, 'stock' => 150, 'sku' => 'GXP10A-CHA-128'],
                ]
            ],
            [
                'nombre' => 'Fairphone 6',
                'brand' => 'Fairphone',
                'descripcion' => 'El smartphone más sostenible y reparable del mundo.',
                'variantes' => [
                    ['color' => 'Matte Black', 'almacenamiento' => '8 GB RAM + 256 GB ROM', 'precio' => 749.00, 'stock' => 15, 'sku' => 'FP6-BLK-256'],
                ]
            ],
            [
                'nombre' => 'OnePlus Nord 5',
                'brand' => 'OnePlus',
                'descripcion' => 'Todo lo que necesitas, exactamente lo que quieres.',
                'variantes' => [
                    ['color' => 'Jade Green', 'almacenamiento' => '12 GB RAM + 256 GB ROM', 'precio' => 499.00, 'stock' => 45, 'sku' => 'OPN5-GRN-256'],
                ]
            ],
            [
                'nombre' => 'Nokia XR30',
                'brand' => 'Nokia',
                'descripcion' => 'Diseñado para durar, resistente a caídas y agua.',
                'variantes' => [
                    ['color' => 'Forest Green', 'almacenamiento' => '6 GB RAM + 128 GB ROM', 'precio' => 449.00, 'stock' => 20, 'sku' => 'NKXR30-GRN-128'],
                ]
            ],
        ];

        foreach ($phones as $phoneData) {
            $variantes = $phoneData['variantes'];
            unset($phoneData['variantes']);
            
            $phoneData['id_categoria'] = $categoriaId;
            $producto = Producto::create($phoneData);

            foreach ($variantes as $variantData) {
                $variantData['id_producto'] = $producto->id_producto;
                // Simplified image path based on name
                $variantData['imagen'] = 'assets/' . str_replace([' ', '(', ')'], '', $producto->nombre) . '.png';
                VarianteProducto::create($variantData);
            }
        }
    }
}
