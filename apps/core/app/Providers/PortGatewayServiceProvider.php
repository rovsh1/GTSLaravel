<?php

namespace App\Core\Providers;

use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;
use Sdk\Module\PortGateway\Client\Client as PortGateway;

class PortGatewayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PortGatewayInterface::class, PortGateway::class);
        $this->app->alias(PortGatewayInterface::class, 'portGateway');

        $this->registerAdapters();
    }

    private function registerAdapters(): void
    {
    }
}
