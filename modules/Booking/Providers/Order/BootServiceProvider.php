<?php

namespace Module\Booking\Providers\Order;

use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            \Module\Booking\Domain\Order\Repository\OrderRepositoryInterface::class,
            \Module\Booking\Infrastructure\Order\Repository\OrderRepository::class,
        );

        $this->app->singleton(
            \Module\Booking\Domain\Order\Repository\GuestRepositoryInterface::class,
            \Module\Booking\Infrastructure\Order\Repository\GuestRepository::class,
        );
    }
}
