<?php

namespace App\Core\Providers;

use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;
use Sdk\Module\Foundation\Providers\ModuleServiceProvider as ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    protected array $sharedBindings = [
        PortGatewayInterface::class
        //ModulesBusInterface::class
    ];

    public function boot()
    {
        $this->registerModulesDependencies($this->app->modules());
    }
}
