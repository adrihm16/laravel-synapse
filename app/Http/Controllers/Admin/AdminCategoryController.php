<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\Categoria;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of categories with search and product count.
     */
    public function index(Request $request)
    {
        $categories = Categoria::withCount('productos')
            ->filter($request->only('search'))
            ->orderBy('nombre')
            ->paginate(15);

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
        $this->categoryService->createCategory($request->validated(), $request);

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
        $this->categoryService->updateCategory($category, $request->validated(), $request);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Soft-delete the specified category.
     */
    public function destroy(Categoria $category)
    {
        if (!$this->categoryService->deleteCategory($category)) {
            $productCount = $category->productos()->count();
            return redirect()->route('admin.categories.index')
                ->with('error', "No se puede eliminar: esta categoría tiene {$productCount} producto(s) asociado(s).");
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
