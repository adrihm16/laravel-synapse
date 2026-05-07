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

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria', 'id_categoria');
    }

    public function getImagenAttribute($value)
    {
        // If an image was uploaded via the admin panel (stored in 'imagen' column)
        if ($value) {
            return Storage::url($value);
        }

        // Fallback to legacy hardcoded assets
        $catImages = [
            'Smartphones' => 'nothingPhone1.png',
            'Ordenadores' => 'macbookAir.png',
            'Tablets'     => 'ipadPro.png',
            'Accesorios'  => 'pixelWatch4.png',
            'Hogar'       => 'CafeteraXiaomi.png'
        ];
        
        $catImg = $catImages[$this->nombre] ?? 'iPadPro.png';
        return asset('assets/' . $catImg);
    }
}
