<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VarianteValor extends Model
{
    protected $table = 'variante_valores';
    public $timestamps = false;

    protected $fillable = [
        'id_variante',
        'id_valor',
    ];
}
