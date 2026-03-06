<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'nombre',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria', 'id_categoria');
    }

    public function getImagenAttribute()
    {
        $catImages = [
            'Smartphones' => 'nothingPhone1.png',
            'Ordenadores' => 'macbookAir.png',
            'Tablets' => 'ipadPro.png',
            'Accesorios' => 'pixelWatch4.png',
            'Hogar' => 'CafeteraXiaomi.png'
        ];
        
        $catImg = $catImages[$this->nombre] ?? 'iPadPro.png';
        return asset('assets/' . $catImg);
    }
}
