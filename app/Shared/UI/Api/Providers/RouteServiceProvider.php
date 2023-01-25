<?php

namespace GTS\Shared\UI\Api\Providers;

use Illuminate\Support\Facades\Route;
use GTS\Shared\UI\Common\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        $this->routes(function () {
            $routes = $this->getModulesRoutes('Api');

            Route::middleware(['api'])->group($routes);
        });
    }
}
