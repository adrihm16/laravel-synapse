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
        'precio_base',
        'destacado',
    ];

    protected $casts = [
        'destacado' => 'boolean',
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
        return $this->hasMany(Variante::class, 'id_producto', 'id_producto');
    }

    public function gruposOpciones()
    {
        return $this->hasMany(GrupoOpcionProducto::class, 'id_producto', 'id_producto')->orderBy('orden', 'asc');
    }

    // Global gallery images (not tied to any color value)
    public function imagenes()
    {
        return $this->hasMany(ImagenProducto::class, 'id_producto', 'id_producto')
            ->whereNull('id_valor')
            ->orderBy('orden', 'asc');
    }

    // All images including per-color ones (for admin)
    public function todasImagenes()
    {
        return $this->hasMany(ImagenProducto::class, 'id_producto', 'id_producto')
            ->orderBy('orden', 'asc');
    }

    public function getPrecioAttribute()
    {
        // Use loaded collection when available to avoid N+1 in product listings
        $minPrecio = $this->relationLoaded('variantes')
            ? $this->variantes->min('precio')
            : $this->variantes()->min('precio');
        return $minPrecio ? (float) $minPrecio : (float) $this->precio_base;
    }

    public function getImagenPrincipalAttribute()
    {
        // 1. First global gallery image
        $firstGalleryImage = $this->relationLoaded('imagenes')
            ? $this->imagenes->first()
            : $this->imagenes()->first();
        if ($firstGalleryImage) {
            return $firstGalleryImage->url;
        }

        // 2. First image from any color-specific gallery
        $firstColorImage = $this->relationLoaded('todasImagenes')
            ? $this->todasImagenes->whereNotNull('id_valor')->sortBy('orden')->first()
            : $this->todasImagenes()->whereNotNull('id_valor')->orderBy('orden')->first();
        if ($firstColorImage) {
            return $firstColorImage->url;
        }

        // 3. Single thumbnail on a color value
        $colorGroup = $this->relationLoaded('gruposOpciones')
            ? $this->gruposOpciones->where('tipo', 'color')->first()
            : $this->gruposOpciones()->where('tipo', 'color')->first();
        if ($colorGroup) {
            $firstColorValue = $colorGroup->relationLoaded('valores')
                ? $colorGroup->valores->whereNotNull('imagen')->first()
                : $colorGroup->valores()->whereNotNull('imagen')->first();
            if ($firstColorValue) {
                return $firstColorValue->imagen_url;
            }
        }

        // 4. Asset default
        return asset('assets/' . str_replace(' ', '', $this->nombre) . '.png');
    }

    /**
     * Scope for filtering products.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        });

        $query->when($filters['id_categoria'] ?? null, function ($query, $id_categoria) {
            $query->where('id_categoria', $id_categoria);
        });

        // 'destacado' filter accepts: '1' = only featured, '0' = only non-featured
        if (array_key_exists('destacado', $filters) && $filters['destacado'] !== null && $filters['destacado'] !== '') {
            $query->where('destacado', (bool) $filters['destacado']);
        }
    }
}
