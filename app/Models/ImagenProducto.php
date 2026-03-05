<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenProducto extends Model
{
    protected $table = 'imagen_productos';
    protected $primaryKey = 'id_imagen';

    protected $fillable = [
        'id_producto',
        'ruta',
        'orden'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
