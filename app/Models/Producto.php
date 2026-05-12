<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_categoria',
        'brand',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id_producto';
    }

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
