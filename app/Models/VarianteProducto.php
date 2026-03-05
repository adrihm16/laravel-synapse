<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarianteProducto extends Model
{
    use HasFactory;

    protected $table = 'variantes_producto';
    protected $primaryKey = 'id_variante';

    protected $fillable = [
        'id_producto',
        'color',
        'almacenamiento',
        'precio',
        'stock',
        'imagen',
        'sku',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
