<?php

namespace Module\Hotel\Quotation\Providers;

use Module\Hotel\Quotation\Domain\Adapter\HotelAdapterInterface;
use Module\Hotel\Quotation\Domain\Repository\QuotaRepositoryInterface;
use Module\Hotel\Quotation\Infrastructure\Adapter\HotelAdapter;
use Module\Hotel\Quotation\Infrastructure\Repository\QuotaRepository;
use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(QuotaRepositoryInterface::class, QuotaRepository::class);
        $this->app->singleton(HotelAdapterInterface::class, HotelAdapter::class);
    }
}
