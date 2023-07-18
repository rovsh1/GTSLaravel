<?php

namespace Module\Booking\Airport\Providers;

use Module\Booking\Airport\Application\Factory\BookingDtoFactory;
use Module\Booking\Airport\Domain;
use Module\Booking\Airport\Infrastructure;
use Module\Booking\Common\Application\Factory\BookingDtoFactoryInterface;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            Domain\Repository\BookingRepositoryInterface::class,
            Infrastructure\Repository\BookingRepository::class
        );
        $this->app->singleton(
            BookingDtoFactoryInterface::class,
            BookingDtoFactory::class
        );
    }
}
