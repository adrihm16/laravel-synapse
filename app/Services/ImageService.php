<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ImageService
{
    protected int $quality = 85;

    /**
     * Convert an uploaded image to WebP and store it under the given directory on the public disk.
     * Returns the relative storage path (e.g. "categories/ab12cd.webp").
     */
    public function storeAsWebp(UploadedFile $file, string $directory, ?int $quality = null): string
    {
        $image = (new ImageManager(new Driver()))->decode($file->getRealPath());
        $encoded = $image->encode(new WebpEncoder($quality ?? $this->quality));

        $filename = Str::random(40) . '.webp';
        $path = trim($directory, '/') . '/' . $filename;

        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    /**
     * Delete a previously stored file from the public disk.
     * Safe-guards against legacy `assets/...` paths shipped with the app.
     */
    public function delete(?string $path): void
    {
        if (! $path || Str::startsWith($path, 'assets/')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
