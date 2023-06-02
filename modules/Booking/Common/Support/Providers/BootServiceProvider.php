<?php

namespace Module\Booking\Common\Support\Providers;

use Module\Booking\Common\Domain;
use Module\Booking\Common\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public $singletons = [
        Domain\Repository\BookingRepositoryInterface::class => Infrastructure\Repository\BookingRepository::class,
        Domain\Adapter\OrderAdapterInterface::class => Infrastructure\Adapter\OrderAdapter::class,
    ];
}
