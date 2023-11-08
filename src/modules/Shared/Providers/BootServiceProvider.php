<?php

namespace Module\Shared\Providers;

use Module\Shared\Contracts\Service\ApplicationContextInterface;
use Module\Shared\Infrastructure\Service\ApplicationContext\ApplicationContextManager;
use Module\Booking\Shared\Providers\BootServiceProvider as BookingServiceProvider;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
//        $this->app->register(IntegrationEventGatewayServiceProvider::class);

        $this->app->singleton(ApplicationContextInterface::class, ApplicationContextManager::class);

        $this->app->register(AdapterServiceProvider::class);
        $this->app->register(ServicesServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        //TODO Временно, вынести отдельно
        $this->app->register(BookingServiceProvider::class);
    }
}
