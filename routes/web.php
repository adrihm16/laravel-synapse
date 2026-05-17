<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminStatsController;
use App\Http\Controllers\Admin\AdminHeroBannerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/catalogo', [ProductController::class, 'index'])->name('catalog.index');
Route::get('/producto/{id}', [ProductController::class, 'show'])->name('product.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrito/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/carrito/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/carrito/update', [CartController::class, 'update'])->name('cart.update');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])
    ->prefix('dashboard')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Phase 1 — User Management
        Route::resource('users', AdminUserController::class)->except(['show']);

        // Phase 2
        Route::resource('categories', AdminCategoryController::class)->except(['show']);

        // Phase 3 — Product Management
        Route::resource('products', AdminProductController::class);

        // Orders
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{pedido}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{pedido}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Sales Stats
        Route::get('stats', [AdminStatsController::class, 'index'])->name('stats.index');

        // Hero banner
        Route::patch('hero-banner/{banner}', [AdminHeroBannerController::class, 'update'])
            ->name('hero-banner.update');
    });

require __DIR__.'/auth.php';
