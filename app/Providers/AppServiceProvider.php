<?php

namespace App\Providers;

use App\Models\Carrito;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading($this->app->environment('local'));

        View::composer('components.store-header', function ($view) {
            $cartCount = 0;
            if ($user = auth()->user()) {
                $cartCount = (int) Carrito::where('id_usuario', $user->id)->sum('cantidad');
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
