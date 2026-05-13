<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ValorOpcionProducto extends Model
{
    use HasFactory;

    protected $table = 'valores_opcion_producto';
    protected $primaryKey = 'id_valor';

    protected $fillable = [
        'id_grupo',
        'nombre',
        'hex_code',
        'imagen',
        'precio_extra',
        'orden',
    ];

    public function grupo()
    {
        return $this->belongsTo(GrupoOpcionProducto::class, 'id_grupo', 'id_grupo');
    }

    public function getImagenUrlAttribute()
    {
        if (!$this->imagen) return null;
        if (str_starts_with($this->imagen, 'http')) return $this->imagen;
        if (str_starts_with($this->imagen, 'assets/')) return asset($this->imagen);
        return Storage::url($this->imagen);
    }
}
