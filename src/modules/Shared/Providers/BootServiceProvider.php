<?php

namespace Module\Shared\Providers;

use Sdk\Module\Support\ServiceProvider;
use Sdk\Shared\Contracts\Service\ApplicationContextInterface;
use Sdk\Shared\Support\ApplicationContext\ApplicationContextManager;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
//        $this->app->register(IntegrationEventGatewayServiceProvider::class);

        $this->app->singleton(ApplicationContextInterface::class, ApplicationContextManager::class);

        $this->app->register(AdapterServiceProvider::class);
        $this->app->register(ServicesServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }
}
