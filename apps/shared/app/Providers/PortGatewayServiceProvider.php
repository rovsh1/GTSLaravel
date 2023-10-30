<?php

namespace App\Shared\Providers;

use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;
use Sdk\Module\PortGateway\Client\Client as PortGateway;
use Sdk\Module\Support\ServiceProvider;

/**
 * @deprecated
 */
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
