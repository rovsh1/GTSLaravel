<?php

namespace Module\Shared\Providers;

use Module\Shared\Contracts\Service\ApplicationContextInterface;
use Module\Shared\Infrastructure\Service\ApplicationContext\ApplicationContextManager;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ApplicationContextInterface::class, ApplicationContextManager::class);

        $this->app->register(AdapterServiceProvider::class);
        $this->app->register(ServicesServiceProvider::class);
        $this->app->register(NotificationServiceProvider::class);
    }
}
