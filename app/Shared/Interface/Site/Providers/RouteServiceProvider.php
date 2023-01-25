<?php

namespace GTS\Shared\Interface\Site\Providers;

use GTS\Shared\Interface\Common\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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
