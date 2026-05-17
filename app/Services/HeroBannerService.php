<?php

namespace App\Services;

use App\Models\HeroBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HeroBannerService
{
    public function __construct(protected ImageService $images) {}

    public function update(HeroBanner $banner, array $data, Request $request): HeroBanner
    {
        return DB::transaction(function () use ($banner, $data, $request) {
            if ($request->hasFile('imagen_desktop')) {
                $newPath = $this->images->storeAsWebp($request->file('imagen_desktop'), 'hero');
                $this->images->delete($banner->imagen_desktop);
                $banner->imagen_desktop = $newPath;
            }

            if ($request->hasFile('imagen_mobile')) {
                $newPath = $this->images->storeAsWebp($request->file('imagen_mobile'), 'hero');
                $this->images->delete($banner->imagen_mobile);
                $banner->imagen_mobile = $newPath;
            }

            $banner->enlace = $data['enlace'] ?? null;
            $banner->titulo = $data['titulo'] ?? null;
            $banner->activo = (bool) ($data['activo'] ?? false);

            $banner->save();

            Cache::forget('home.hero_banner');

            return $banner;
        });
    }
}
