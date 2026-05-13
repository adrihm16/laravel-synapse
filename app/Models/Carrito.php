<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $table = 'carrito';
    protected $primaryKey = 'id_carrito';

    protected $fillable = [
        'id_usuario',
        'id_variante',
        'cantidad',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function variante()
    {
        return $this->belongsTo(Variante::class, 'id_variante', 'id_variante');
    }
}
