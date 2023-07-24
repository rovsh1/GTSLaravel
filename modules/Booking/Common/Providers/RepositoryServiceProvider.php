<?php

namespace Module\Booking\Common\Providers;

use Module\Booking\Common\Domain\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Common\Domain\Repository\InvoiceRepositoryInterface;
use Module\Booking\Common\Domain\Repository\RequestRepositoryInterface;
use Module\Booking\Common\Domain\Repository\VoucherRepositoryInterface;
use Module\Booking\Common\Infrastructure\Repository\BookingChangesLogRepository;
use Module\Booking\Common\Infrastructure\Repository\InvoiceRepository;
use Module\Booking\Common\Infrastructure\Repository\RequestRepository;
use Module\Booking\Common\Infrastructure\Repository\VoucherRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->singleton(VoucherRepositoryInterface::class, VoucherRepository::class);
        $this->app->singleton(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->singleton(BookingChangesLogRepositoryInterface::class, BookingChangesLogRepository::class);
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
    }
}
