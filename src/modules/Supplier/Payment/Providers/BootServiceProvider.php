<?php

namespace Module\Supplier\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Supplier\Payment\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Supplier\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Supplier\Payment\Infrastructure\Repository\BookingRepository;
use Module\Supplier\Payment\Infrastructure\Repository\PaymentRepository;

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
        $this->app->singleton(BookingRepositoryInterface::class, BookingRepository::class);
    }
}
