<?php

namespace Module\Generic\NotificationManager\Providers;

use Module\Generic\NotificationManager\Domain\Factory\NotifiableFactoryInterface;
use Module\Generic\NotificationManager\Infrastructure\Factory\NotifiableFactory;
use Sdk\Module\Support\ServiceProvider;

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
