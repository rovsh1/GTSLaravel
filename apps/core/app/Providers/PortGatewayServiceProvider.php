<?php

namespace App\Core\Providers;

use App\Core\Support\Adapters\FileAdapter;
use Custom\Framework\Contracts\PortGateway\PortGatewayInterface;
use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Custom\Framework\PortGateway\Client\Client as PortGateway;

class PortGatewayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PortGatewayInterface::class, PortGateway::class);
        $this->app->alias(PortGatewayInterface::class, 'portGateway');

        $this->registerAdapters();
    }

    private function registerAdapters()
    {
        $this->app->singleton('file-adapter', FileAdapter::class);
    }
}
