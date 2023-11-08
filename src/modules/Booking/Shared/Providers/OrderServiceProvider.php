<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Infrastructure\Order\Repository\GuestRepository;
use Module\Booking\Shared\Infrastructure\Order\Repository\OrderRepository;
use Sdk\Module\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->singleton(GuestRepositoryInterface::class, GuestRepository::class);
    }
}
