<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of categories with search and product count.
     */
    public function index(Request $request)
    {
        $query = Categoria::withCount('productos');

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->input('search') . '%');
        }

        $categories = $query->orderBy('nombre')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('categories', 'public');
        }

        Categoria::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Categoria $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(StoreCategoryRequest $request, Categoria $category)
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            // Delete old image from storage if it exists
            if ($category->getRawOriginal('imagen')) {
                Storage::disk('public')->delete($category->getRawOriginal('imagen'));
            }

            $data['imagen'] = $request->file('imagen')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Soft-delete the specified category.
     */
    public function destroy(Categoria $category)
    {
        $productCount = $category->productos()->count();

        if ($productCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', "No se puede eliminar: esta categoría tiene {$productCount} producto(s) asociado(s).");
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
