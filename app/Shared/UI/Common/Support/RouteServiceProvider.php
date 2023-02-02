<?php

namespace GTS\Shared\UI\Common\Support;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected function moduleUIRoutes($group, $moduleName, $port)
    {
        $this->routes(function () use ($group, $moduleName, $port) {
            Route::group($group, module($moduleName)->path('UI/' . $port . '/routes.php'));
        });
    }
}
