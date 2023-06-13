<?php

namespace Module\Booking\Airport\Providers;

use Module\Booking\Common\Support\Providers\BootServiceProvider as ServiceProvider;
use Module\Booking\Hotel\Domain;
use Module\Booking\Hotel\Infrastructure;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
//        $this->app->singleton(
//            FileStorageAdapterInterface::class,
//            FileStorageAdapter::class
//        );
    }
}
