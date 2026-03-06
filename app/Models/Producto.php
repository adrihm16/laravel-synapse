<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_categoria',
        'brand',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function variantes()
    {
        return $this->hasMany(VarianteProducto::class, 'id_producto', 'id_producto');
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenProducto::class, 'id_producto', 'id_producto')->orderBy('orden', 'asc');
    }

    public function getPrecioAttribute()
    {
        $variante = $this->variantes->first();
        return $variante ? (float) $variante->precio : 0.0;
    }

    public function getImagenPrincipalAttribute()
    {
        $variante = $this->variantes->first();
        if ($variante && $variante->imagen) {
            return asset($variante->imagen);
        }
        return asset('assets/' . str_replace(' ', '', $this->nombre) . '.png');
    }

    public function getColoresUnicosAttribute()
    {
        return $this->variantes->unique('color');
    }
}
