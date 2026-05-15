<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class ImagenProducto extends Model
{
    protected $table = 'imagen_productos';
    protected $primaryKey = 'id_imagen';

    protected $fillable = [
        'id_producto',
        'id_valor',
        'ruta',
        'orden',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function valor()
    {
        return $this->belongsTo(ValorOpcionProducto::class, 'id_valor', 'id_valor');
    }

    public function getUrlAttribute()
    {
        if (!$this->ruta) return null;
        if (str_starts_with($this->ruta, 'http')) return $this->ruta;
        if (str_starts_with($this->ruta, 'assets/')) return asset($this->ruta);
        return Storage::url($this->ruta);
    }
}
