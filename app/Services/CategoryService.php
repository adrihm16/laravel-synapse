<?php

namespace App\Services;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
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
            $data['imagen'] = $request->file('imagen')->store('categories', 'public');
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
            // Delete old image from storage if it exists
            if ($category->getRawOriginal('imagen')) {
                Storage::disk('public')->delete($category->getRawOriginal('imagen'));
            }

            $data['imagen'] = $request->file('imagen')->store('categories', 'public');
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
