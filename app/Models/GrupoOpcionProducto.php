<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoOpcionProducto extends Model
{
    use HasFactory;

    protected $table = 'grupos_opcion_producto';
    protected $primaryKey = 'id_grupo';

    protected $fillable = [
        'id_producto',
        'nombre',
        'tipo',
        'orden',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function valores()
    {
        return $this->hasMany(ValorOpcionProducto::class, 'id_grupo', 'id_grupo')->orderBy('orden', 'asc');
    }
}
