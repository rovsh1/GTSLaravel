<?php

namespace Module\Booking\Pricing\Providers;

use Module\Booking\Pricing\Domain\Booking\Adapter\HotelPricingAdapterInterface;
use Module\Booking\Pricing\Infrastructure\Adapter\HotelPricingAdapter;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(HotelPricingAdapterInterface::class, HotelPricingAdapter::class);
    }
}
