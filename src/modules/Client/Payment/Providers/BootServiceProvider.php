<?php

namespace Module\Client\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Client\Payment\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Client\Payment\Infrastructure\Repository\OrderRepository;
use Module\Client\Payment\Infrastructure\Repository\PaymentRepository;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(DomainEventServiceProvider::class);
        $this->app->register(IntegrationEventServiceProvider::class);
    }

    public function boot()
    {
        $this->app->singleton(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
    }
}
