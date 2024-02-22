<?php

namespace Module\Booking\Pricing\Providers;

use Module\Booking\Pricing\Domain\Booking\Adapter\HotelPricingAdapterInterface;
use Module\Booking\Pricing\Infrastructure\Adapter\HotelPricingAdapter;
use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(SharedBookingServiceProvider::class);
    }

    public function boot(): void
    {
        $this->app->singleton(HotelPricingAdapterInterface::class, HotelPricingAdapter::class);
    }
}
