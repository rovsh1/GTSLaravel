<?php

namespace Module\Services\NotificationManager\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Services\NotificationManager\Domain\Factory\NotifiableFactoryInterface;
use Module\Services\NotificationManager\Infrastructure\Factory\NotifiableFactory;

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
