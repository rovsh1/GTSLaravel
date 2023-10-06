<?php

namespace Module\Booking\Providers\BookingRequest;

use Module\Booking\Domain\BookingRequest\Repository\RequestRepositoryInterface;
use Module\Booking\Infrastructure\BookingRequest\Repository\RequestRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
    }
}
