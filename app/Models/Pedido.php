<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function getRouteKeyName(): string
    {
        return 'id_pedido';
    }

    public function getReferenciaAttribute(): string
    {
        return 'SYN-' . str_pad($this->id_pedido, 6, '0', STR_PAD_LEFT);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            // Strip "SYN-" prefix if user typed the reference format
            $numericId = ltrim(str_ireplace('SYN-', '', $s), '0') ?: '0';

            $query->where(function (Builder $q) use ($s, $numericId) {
                $q->where('id_pedido', $numericId)
                  ->orWhereHas('user', fn (Builder $u) => $u
                      ->where('name', 'like', "%{$s}%")
                      ->orWhere('email', 'like', "%{$s}%")
                  );
            });
        }

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        if (!empty($filters['from'])) {
            $query->whereDate('fecha', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('fecha', '<=', $filters['to']);
        }

        return $query;
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
