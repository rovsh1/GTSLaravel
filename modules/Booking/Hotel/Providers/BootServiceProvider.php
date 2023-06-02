<?php

namespace Module\Booking\Hotel\Providers;

use Module\Booking\Common\Domain\Factory\DocumentGeneratorFactory;
use Module\Booking\Common\Support\Providers\BootServiceProvider as ServiceProvider;
use Module\Booking\Hotel\Domain;
use Module\Booking\Hotel\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Hotel\Infrastructure;
use Module\Booking\Hotel\Infrastructure\Adapter\FileStorageAdapter;
use Sdk\Module\Contracts\ModuleInterface;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(
            FileStorageAdapterInterface::class,
            FileStorageAdapter::class
        );
        $this->app->singleton(
            Domain\Adapter\HotelAdapterInterface::class,
            Infrastructure\Adapter\HotelAdapter::class
        );
        $this->app->singleton(
            Domain\Adapter\HotelRoomAdapterInterface::class,
            Infrastructure\Adapter\HotelRoomAdapter::class
        );
        $this->app->singleton(
            Domain\Repository\DetailsRepositoryInterface::class,
            Infrastructure\Repository\DetailsRepository::class
        );
        $this->app->singleton(
            DocumentGeneratorFactory::class,
            fn(ModuleInterface $module) => new DocumentGeneratorFactory($module->config('templates_path'))
        );
//        $this->app->singleton(
//            Domain\Repository\RoomRepositoryInterface::class,
//            Infrastructure\Repository\RoomRepository::class
//        );
    }
}
