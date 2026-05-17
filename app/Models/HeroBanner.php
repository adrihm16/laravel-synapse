<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HeroBanner extends Model
{
    protected $table = 'hero_banners';
    protected $primaryKey = 'id_banner';

    protected $fillable = [
        'imagen_desktop',
        'imagen_mobile',
        'enlace',
        'titulo',
        'activo',
        'orden',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'id_banner';
    }

    public function getImagenDesktopUrlAttribute(): string
    {
        return $this->resolveImageUrl($this->imagen_desktop);
    }

    public function getImagenMobileUrlAttribute(): string
    {
        return $this->resolveImageUrl($this->imagen_mobile);
    }

    protected function resolveImageUrl(?string $value): string
    {
        if (!$value) {
            return '';
        }
        if (Str::startsWith($value, 'assets/')) {
            return asset($value);
        }
        return Storage::url($value);
    }
}
