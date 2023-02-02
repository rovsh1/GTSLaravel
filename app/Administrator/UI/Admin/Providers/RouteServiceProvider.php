<?php

namespace GTS\Administrator\UI\Admin\Providers;

use GTS\Shared\UI\Common\Support\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->moduleUIRoutes([
            'middleware' => ['web', 'admin']
        ], 'Administrator', 'Admin');
    }
}
