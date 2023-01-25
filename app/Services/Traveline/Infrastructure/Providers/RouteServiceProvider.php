<?php

namespace GTS\Services\Traveline\Infrastructure\Providers;

use GTS\Shared\Interface\Common\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        $this->routes(function () {
            $routes = $this->getModulesRoutes('Api');

            Route::middleware(['web'])->group($routes);
        });
    }
}
