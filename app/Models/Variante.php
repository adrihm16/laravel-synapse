<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variante extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'variantes';
    protected $primaryKey = 'id_variante';

    protected $fillable = [
        'id_producto',
        'precio',
        'stock',
        'sku',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function valores()
    {
        return $this->belongsToMany(
            ValorOpcionProducto::class,
            'variante_valores',
            'id_variante',
            'id_valor'
        );
    }

    public function getOpcionesTextAttribute()
    {
        return $this->valores->pluck('nombre')->join(', ');
    }
}
