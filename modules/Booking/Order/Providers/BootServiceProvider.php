<?php

namespace Module\Booking\Order\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Booking\Hotel\Domain;
use Module\Booking\Hotel\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Hotel\Infrastructure;
use Module\Booking\Hotel\Infrastructure\Adapter\FileStorageAdapter;
use Module\Booking\Hotel\Providers\EventServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {

    }
}
