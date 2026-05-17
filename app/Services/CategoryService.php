<?php

namespace App\Services;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    public function __construct(protected ImageService $images) {}

    protected function forgetCategoryCaches(): void
    {
        Cache::forget('home.categorias');
        Cache::forget('catalog.categorias');
    }

    /**
     * Create a new category with image upload.
     */
    public function createCategory(array $data, Request $request): Categoria
    {
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $this->images->storeAsWebp($request->file('imagen'), 'categories');
        }

        $category = Categoria::create($data);
        $this->forgetCategoryCaches();
        return $category;
    }

    /**
     * Update an existing category with image upload.
     */
    public function updateCategory(Categoria $category, array $data, Request $request): Categoria
    {
        if ($request->hasFile('imagen')) {
            $this->images->delete($category->getRawOriginal('imagen'));
            $data['imagen'] = $this->images->storeAsWebp($request->file('imagen'), 'categories');
        }

        $category->update($data);
        $this->forgetCategoryCaches();

        return $category;
    }

    /**
     * Delete a category if it has no products.
     */
    public function deleteCategory(Categoria $category): bool
    {
        if ($category->productos()->count() > 0) {
            return false;
        }

        $deleted = $category->delete();
        if ($deleted) {
            $this->forgetCategoryCaches();
        }
        return $deleted;
    }
}
