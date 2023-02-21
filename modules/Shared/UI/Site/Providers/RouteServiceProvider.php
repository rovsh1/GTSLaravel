<?php

namespace Module\Shared\UI\Site\Providers;

use Illuminate\Support\Facades\Route;

use Module\Shared\UI\Common\Support\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        $this->routes(function () {
            $routes = [];
            //$routes[] = app_path('Shared/UI/Admin/routes.php');
            $routes[] = app_path('Services/FileStorage/UI/Site/routes.php');

            Route::middleware(['web', 'site'])->group($routes);
        });
    }
}
