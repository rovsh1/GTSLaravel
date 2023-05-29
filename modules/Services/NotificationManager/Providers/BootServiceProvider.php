<?php

namespace Module\Services\NotificationManager\Providers;

use Module\Services\NotificationManager\Domain\Factory\NotifiableFactoryInterface;
use Module\Services\NotificationManager\Infrastructure\Factory\NotifiableFactory;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function boot()
    {
        $this->app->register(NotifiableFactoryInterface::class, NotifiableFactory::class);
    }
}
