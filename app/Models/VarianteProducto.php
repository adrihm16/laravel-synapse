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

    public function getColorClassAttribute()
    {
        $bgClass = 'bg-gray-900';
        if (!$this->color) return $bgClass;
        
        $cLower = strtolower($this->color);
        if(str_contains($cLower, 'sand')) $bgClass = 'bg-[#dcbfa8]';
        if(str_contains($cLower, 'violet')) $bgClass = 'bg-[#e0d6ff]';
        if(str_contains($cLower, 'white')) $bgClass = 'bg-white border border-gray-300';
        if(str_contains($cLower, 'blue')) $bgClass = 'bg-blue-300';
        if(str_contains($cLower, 'titanium')) $bgClass = 'bg-[#898886]';
        return $bgClass;
    }
}
