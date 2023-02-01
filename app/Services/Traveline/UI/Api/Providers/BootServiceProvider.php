<?php

namespace GTS\Services\Traveline\UI\Api\Providers;

use GTS\Shared\UI\Common\Support\BootServiceProvider as ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    protected $requiredModules = [
        'Traveline'
    ];

    protected $providers = [
        RouteServiceProvider::class,
    ];
}
