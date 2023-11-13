<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Booking\Adapter\PricingAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\ClientAdapterInterface;
use Module\Booking\Shared\Infrastructure\Adapter\AdministratorAdapter;
use Module\Booking\Shared\Infrastructure\Adapter\ClientAdapter;
use Module\Booking\Shared\Infrastructure\Adapter\PricingAdapter;
use Module\Booking\Shared\Infrastructure\Adapter\SupplierAdapter;
use Sdk\Module\Support\ServiceProvider;

class AdapterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(SupplierAdapterInterface::class, SupplierAdapter::class);
        $this->app->singleton(PricingAdapterInterface::class, PricingAdapter::class);
        $this->app->singleton(ClientAdapterInterface::class, ClientAdapter::class);
        $this->app->singleton(AdministratorAdapterInterface::class, AdministratorAdapter::class);
    }
}
