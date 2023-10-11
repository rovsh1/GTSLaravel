<?php

namespace Module\Shared\Providers;

use Module\Shared\Contracts\Service\ApplicationContextInterface;
use Module\Shared\Infrastructure\Service\ApplicationContext\ApplicationContextManager;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;
use Service\IntegrationEventGateway\IntegrationEventGatewayServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
//        $this->app->register(IntegrationEventGatewayServiceProvider::class);

        $this->app->singleton(ApplicationContextInterface::class, ApplicationContextManager::class);

        $this->app->register(AdapterServiceProvider::class);
        $this->app->register(ServicesServiceProvider::class);
//        $this->app->register(EventServiceProvider::class);
    }
}
