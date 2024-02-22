<?php

namespace Pkg\Supplier\Traveline;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::pattern('id', '[0-9]+');

        $this->routes(function () {
            Route::prefix('traveline')
                ->as('traveline.')
                ->group(__DIR__ . '/../routes/web.php');
        });
    }
}
