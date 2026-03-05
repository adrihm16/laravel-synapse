<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\VarianteProducto;
use App\Models\ImagenProducto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Categorías
        $categorias = [
            'Smartphones',
            'Ordenadores', // Added
            'Tablets',     // Added
            'Accesorios',  // Renamed from Smartwatches
            'Hogar'        // Added (from "Outlet hogar")
        ];

        foreach ($categorias as $cat) {
            Categoria::create(['nombre' => $cat]);
        }

        // 2. Crear Usuarios
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@synapse.com',
            'password' => Hash::make('password123'),
            'rol' => 'admin',
        ]);

        $user = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@correo.com',
            'password' => Hash::make('password123'),
            'rol' => 'cliente',
        ]);

        // --- PRODUCTOS ---

        // Producto 1: One Plus 15
        $producto = Producto::create([
            'nombre' => 'One Plus 15',
            'descripcion' => 'El último flagship con cámara Hasselblad y rendimiento extremo.',
            'id_categoria' => Categoria::where('nombre', 'Smartphones')->first()->id_categoria
        ]);

        VarianteProducto::create([
            'id_producto' => $producto->id_producto,
            'color' => 'Sand Storm',
            'almacenamiento' => '16 GB RAM + 512 GB ROM',
            'precio' => 1029.00,
            'stock' => 50,
            'imagen' => 'assets/Oneplus15.png',
            'sku' => 'OP15-SAND-512'
        ]);

        VarianteProducto::create([
            'id_producto' => $producto->id_producto,
            'color' => 'Infinite Black',
            'almacenamiento' => '16 GB RAM + 512 GB ROM',
            'precio' => 1029.00,
            'stock' => 30,
            'imagen' => 'assets/Oneplus15.png',
            'sku' => 'OP15-BLK-512'
        ]);

        VarianteProducto::create([
            'id_producto' => $producto->id_producto,
            'color' => 'Ultra Violet',
            'almacenamiento' => '12 GB RAM + 256 GB ROM',
            'precio' => 949.00,
            'stock' => 0,
            'imagen' => 'assets/Oneplus15.png',
            'sku' => 'OP15-VIO-256'
        ]);
        
        // Producto 2: iPhone 17 Pro Max
        $producto2 = Producto::create([
            'nombre' => 'iPhone 17 Pro Max',
            'descripcion' => 'El mejor iPhone creado hasta la fecha con Titanium y A19 Bionic.',
            'id_categoria' => Categoria::where('nombre', 'Smartphones')->first()->id_categoria
        ]);

        VarianteProducto::create([
            'id_producto' => $producto2->id_producto,
            'color' => 'Natural Titanium',
            'almacenamiento' => '12 GB RAM + 256 GB ROM',
            'precio' => 1700.00,
            'stock' => 15,
            'imagen' => 'assets/iPhone17ProMax.png',
            'sku' => 'IP17PM-NAT-256'
        ]);
        
        // Producto 3: Oppo Find X9 Pro
        $producto3 = Producto::create([
            'nombre' => 'Oppo Find X9 Pro',
            'descripcion' => 'Innovación y diseño con carga ultrarrápida SuperVOOC.',
            'id_categoria' => Categoria::where('nombre', 'Smartphones')->first()->id_categoria
        ]);

        VarianteProducto::create([
            'id_producto' => $producto3->id_producto,
            'color' => 'Pearl White',
            'almacenamiento' => '16 GB RAM + 512 GB ROM',
            'precio' => 1299.00,
            'stock' => 10,
            'imagen' => 'assets/oppoFindX9Pro.png',
            'sku' => 'OPPO-X9P-WHT-512'
        ]);

        // Producto 4: iPhone 17 Air
        $producto4 = Producto::create([
            'nombre' => 'iPhone 17 Air',
            'descripcion' => 'El diseño más fino y ligero de Apple, potencia en tus manos.',
            'id_categoria' => Categoria::where('nombre', 'Smartphones')->first()->id_categoria
        ]);

        VarianteProducto::create([
            'id_producto' => $producto4->id_producto,
            'color' => 'Sky Blue',
            'almacenamiento' => '8 GB RAM + 256 GB ROM',
            'precio' => 1050.00,
            'stock' => 25,
            'imagen' => 'assets/iphone17AirBlue.png',
            'sku' => 'IP17A-BLU-256'
        ]);

        // Producto 5: Google Pixel Watch 4 (Audio/Wearables mockup)
        $producto5 = Producto::create([
            'nombre' => 'Google Pixel Watch 4',
            'descripcion' => 'El smartwatch más inteligente con integración Fitbit premium.',
            'id_categoria' => Categoria::where('nombre', 'Accesorios')->first()->id_categoria
        ]);

        VarianteProducto::create([
            'id_producto' => $producto5->id_producto,
            'color' => 'Obsidian Black',
            'almacenamiento' => 'Bluetooth / Wi-Fi',
            'precio' => 399.00,
            'stock' => 45,
            'imagen' => 'assets/pixelWatch4.png',
            'sku' => 'PW4-OBS'
        ]);

        // Producto 6: Nothing Phone 1
        $producto6 = Producto::create([
            'nombre' => 'Nothing Phone (1)',
            'descripcion' => 'Diseño transparente con la interfaz Glyph única en su clase.',
            'id_categoria' => Categoria::where('nombre', 'Smartphones')->first()->id_categoria
        ]);

        VarianteProducto::create([
            'id_producto' => $producto6->id_producto,
            'color' => 'White',
            'almacenamiento' => '8 GB RAM + 256 GB ROM',
            'precio' => 499.00,
            'stock' => 12,
            'imagen' => 'assets/nothingPhone1.png',
            'sku' => 'NP1-WHT-256'
        ]);

        // Producto 7: iPad Pro
        $producto7 = Producto::create([
            'nombre' => 'iPad Pro M4',
            'descripcion' => 'Potencia desmesurada con el chip M4 y pantalla OLED.',
            'id_categoria' => Categoria::where('nombre', 'Tablets')->first()->id_categoria
        ]);

        VarianteProducto::create([
            'id_producto' => $producto7->id_producto,
            'color' => 'Space Black',
            'almacenamiento' => '16 GB RAM + 1 TB ROM',
            'precio' => 1899.00,
            'stock' => 5,
            'imagen' => 'assets/iPadPro.png',
            'sku' => 'IPAD-PRO-M4-BLK'
        ]);
        
        // Producto 8: Funda
        $producto8 = Producto::create([
            'nombre' => 'Funda Silicona Magnética',
            'descripcion' => 'Funda resistente con anclaje magnético compatible.',
            'id_categoria' => Categoria::where('nombre', 'Accesorios')->first()->id_categoria
        ]);

        VarianteProducto::create([
            'id_producto' => $producto8->id_producto,
            'color' => 'Dark Blue',
            'almacenamiento' => 'Estándar',
            'precio' => 29.99,
            'stock' => 150,
            'imagen' => 'assets/Funda.png',
            'sku' => 'ACC-FUND-BLU'
        ]);

        // --- GALERIA DE IMAGENES ---

        // Images for OnePlus 15 (ID 1)
        ImagenProducto::create(['id_producto' => $producto->id_producto, 'ruta' => 'assets/Oneplus15.png', 'orden' => 1]);
        ImagenProducto::create(['id_producto' => $producto->id_producto, 'ruta' => 'assets/ImagenOnePlus1.jpg', 'orden' => 2]);
        ImagenProducto::create(['id_producto' => $producto->id_producto, 'ruta' => 'assets/ImagenOnePlus2.png', 'orden' => 3]);
        ImagenProducto::create(['id_producto' => $producto->id_producto, 'ruta' => 'assets/ImagenOnePlus3.png', 'orden' => 4]);

        // Images for iPhone 17 Pro Max (ID 2)
        ImagenProducto::create(['id_producto' => $producto2->id_producto, 'ruta' => 'assets/iPhone17ProMax.png', 'orden' => 1]);
        ImagenProducto::create(['id_producto' => $producto2->id_producto, 'ruta' => 'assets/iPhone17ProMax.png', 'orden' => 2]); // Using same as mock

        // Images for Oppo Find X9 Pro (ID 3)
        ImagenProducto::create(['id_producto' => $producto3->id_producto, 'ruta' => 'assets/oppoFindX9Pro.png', 'orden' => 1]);
        
        // Images for iPhone 17 Air (ID 4)
        ImagenProducto::create(['id_producto' => $producto4->id_producto, 'ruta' => 'assets/iphone17AirBlue.png', 'orden' => 1]);

        // Images for Pixel Watch 4 (ID 5)
        ImagenProducto::create(['id_producto' => $producto5->id_producto, 'ruta' => 'assets/pixelWatch4.png', 'orden' => 1]);

        // Images for Nothing Phone 1 (ID 6)
        ImagenProducto::create(['id_producto' => $producto6->id_producto, 'ruta' => 'assets/nothingPhone1.png', 'orden' => 1]);

        // Images for iPad Pro (ID 7)
        ImagenProducto::create(['id_producto' => $producto7->id_producto, 'ruta' => 'assets/iPadPro.png', 'orden' => 1]);

        // Images for Funda (ID 8)
        ImagenProducto::create(['id_producto' => $producto8->id_producto, 'ruta' => 'assets/Funda.png', 'orden' => 1]);
    }
}
