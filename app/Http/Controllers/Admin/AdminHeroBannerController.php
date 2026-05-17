<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHeroBannerRequest;
use App\Models\HeroBanner;
use App\Services\HeroBannerService;

class AdminHeroBannerController extends Controller
{
    protected HeroBannerService $service;

    public function __construct(HeroBannerService $service)
    {
        $this->service = $service;
    }

    public function update(UpdateHeroBannerRequest $request, HeroBanner $banner)
    {
        $this->service->update($banner, $request->validated(), $request);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Banner principal actualizado correctamente.');
    }
}
