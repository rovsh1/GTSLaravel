<?php

namespace GTS\Shared\Infrastructure\Providers;

use Custom\Framework\Foundation\Support\ServiceProvider;
use GTS\Services\PortGateway\Client as PortGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->registerModules();

        $this->app->singleton('portGateway', PortGateway::class);
    }
}
