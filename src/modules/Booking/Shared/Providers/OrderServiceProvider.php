<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Adapter\InvoiceAdapterInterface;
use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Service\OrderStatusStorageInterface;
use Module\Booking\Shared\Infrastructure\Adapter\InvoiceAdapter;
use Module\Booking\Shared\Infrastructure\DbContext\OrderDbContext;
use Module\Booking\Shared\Infrastructure\Repository\GuestRepository;
use Module\Booking\Shared\Infrastructure\Repository\OrderRepository;
use Module\Booking\Shared\Infrastructure\Service\OrderStatusStorage;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->singleton(OrderDbContextInterface::class, OrderDbContext::class);
        $this->app->singleton(GuestRepositoryInterface::class, GuestRepository::class);
        $this->app->singleton(OrderStatusStorageInterface::class, OrderStatusStorage::class);
        $this->app->singleton(InvoiceAdapterInterface::class, InvoiceAdapter::class);
    }
}
