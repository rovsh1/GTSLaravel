<?php

namespace Module\Integration\Traveline\UI\Api\Providers;

use Module\Shared\UI\Common\Support\RouteServiceProvider as ServiceProvider;

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
