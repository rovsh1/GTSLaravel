<?php

namespace Module\Booking\Providers\Shared;

use Module\Booking\Domain\Shared\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Domain\Shared\Repository\RequestRepositoryInterface;
use Module\Booking\Domain\Shared\Repository\VoucherRepositoryInterface;
use Module\Booking\Infrastructure\Shared\Repository\BookingChangesLogRepository;
use Module\Booking\Infrastructure\Shared\Repository\RequestRepository;
use Module\Booking\Infrastructure\Shared\Repository\VoucherRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->singleton(VoucherRepositoryInterface::class, VoucherRepository::class);
        $this->app->singleton(BookingChangesLogRepositoryInterface::class, BookingChangesLogRepository::class);
    }
}
