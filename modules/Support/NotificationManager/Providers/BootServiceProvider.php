<?php

namespace Module\Support\NotificationManager\Providers;

use Module\Support\NotificationManager\Domain\Factory\NotifiableFactoryInterface;
use Module\Support\NotificationManager\Infrastructure\Factory\NotifiableFactory;
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
