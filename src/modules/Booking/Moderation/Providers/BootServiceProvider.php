<?php

namespace Module\Booking\Moderation\Providers;

use Module\Booking\Moderation\Domain\Order\Adapter\InvoiceAdapterInterface;
use Module\Booking\Moderation\Infrastructure\Adapter\InvoiceAdapter;
use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(SharedBookingServiceProvider::class);
    }

    public function boot(): void
    {
        $this->app->singleton(InvoiceAdapterInterface::class, InvoiceAdapter::class);
    }
}
