<?php

namespace GTS\Services\Traveline\UI\Api\Providers;

use Illuminate\Support\Facades\Route;

use GTS\Shared\UI\Common\Support\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->moduleUIRoutes([
            'prefix' => 'traveline',
            'as' => 'traveline.',
            'middleware' => ['api']
        ], 'Traveline', 'Api');
    }
}
