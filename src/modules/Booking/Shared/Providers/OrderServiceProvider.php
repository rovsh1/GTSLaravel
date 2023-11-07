<?php

namespace Module\Booking\Shared\Providers;

use Sdk\Module\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            \Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface::class,
            \Module\Booking\Shared\Infrastructure\Order\Repository\OrderRepository::class,
        );

        $this->app->singleton(
            \Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface::class,
            \Module\Booking\Shared\Infrastructure\Order\Repository\GuestRepository::class,
        );
    }
}
