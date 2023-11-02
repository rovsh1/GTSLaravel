<?php

namespace Module\Support\MailManager\Providers;

use Module\Support\MailManager\Domain\Adapter\HotelBookingAdapterInterface;
use Module\Support\MailManager\Infrastructure\Adapter\HotelBookingAdapter;
use Sdk\Module\Support\ServiceProvider;

class AdapterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(HotelBookingAdapterInterface::class, HotelBookingAdapter::class);
    }
}
