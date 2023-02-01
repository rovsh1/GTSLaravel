<?php

namespace GTS\Services\Traveline\UI\Api\Providers;

use Illuminate\Support\Facades\Route;

use GTS\Shared\UI\Common\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::group([
                'prefix' => 'traveline',
                'as' => 'traveline.',
                'middleware' => ['api']
            ], module('Traveline')->path('UI/Api/routes.php'));
        });
    }
}
