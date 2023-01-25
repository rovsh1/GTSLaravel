<?php

namespace GTS\Shared\Interface\Site\Providers;

use Illuminate\Support\Facades\Route;

use GTS\Shared\Interface\Common\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        $this->routes(function () {
            $routes = $this->getModulesRoutes('Site');

            Route::middleware(['web'])->group($routes);
        });
    }
}
