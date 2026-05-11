<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Categoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'nombre',
        'imagen',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id_categoria';
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria', 'id_categoria');
    }

    public function getImagenAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }

        // Default images mapping based on category name
        $defaults = [
            'Smartphones' => 'assets/Oneplus15.png',
            'Ordenadores' => 'assets/macbookAir.png',
            'Tablets'     => 'assets/ipadPro.png',
            'Accesorios'  => 'assets/pixelWatch4.png',
            'Hogar'       => 'assets/cafeteraXiaomi.png',
        ];

        if (isset($defaults[$this->nombre])) {
            return asset($defaults[$this->nombre]);
        }

        return asset('assets/placeholder-category.png');
    }
}
