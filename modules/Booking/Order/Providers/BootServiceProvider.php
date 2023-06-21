<?php

namespace Module\Booking\Order\Providers;

use Module\Booking\Order\Domain;
use Module\Booking\Order\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            Domain\Repository\OrderRepositoryInterface::class,
            Infrastructure\Repository\OrderRepository::class,
        );
    }
}
