<?php

namespace Module\Hotel\Quotation\Providers;

use Module\Hotel\Quotation\Domain\Adapter\HotelAdapterInterface;
use Module\Hotel\Quotation\Domain\Repository\RoomQuotaRepositoryInterface;
use Module\Hotel\Quotation\Infrastructure\Adapter\HotelAdapter;
use Module\Hotel\Quotation\Infrastructure\Repository\RoomQuotaRepository;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(RoomQuotaRepositoryInterface::class, RoomQuotaRepository::class);
        $this->app->singleton(HotelAdapterInterface::class, HotelAdapter::class);
    }
}
