<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'id_usuario',
        'fecha',
        'total',
        'estado',
        'nombre_envio',
        'direccion',
        'ciudad',
        'codigo_postal',
        'provincia',
        'telefono',
    ];

    /**
     * Formatted order reference (e.g., SYN-000123).
     */
    public function getReferenciaAttribute(): string
    {
        return 'SYN-' . str_pad($this->id_pedido, 6, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }
}
