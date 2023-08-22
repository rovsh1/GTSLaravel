<?php

namespace Module\Support\MailManager\Providers;

use Module\Support\MailManager\Domain\Adapter\HotelBookingAdapterInterface;
use Module\Support\MailManager\Infrastructure\Adapter\HotelBookingAdapter;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class AdapterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(HotelBookingAdapterInterface::class, HotelBookingAdapter::class);
    }
}
