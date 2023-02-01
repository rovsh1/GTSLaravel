<?php

namespace GTS\Shared\UI\Admin\Providers;

use Illuminate\Support\Facades\Route;

use GTS\Shared\UI\Common\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        $this->routes(function () {
            $routes = $this->getModulesRoutes('Admin');
            $routes[] = app_path('Shared/UI/Admin/routes.php');

            Route::middleware(['web', 'admin'])->group($routes);
        });
    }
}
