<?php

namespace Module\Booking\Hotel\Providers;

use Module\Booking\Common\Application\Factory\BookingDtoFactoryInterface;
use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Common\Domain\Factory\DocumentGeneratorFactory;
use Module\Booking\Common\Infrastructure\Adapter\FileStorageAdapter;
use Module\Booking\Hotel\Application\Factory\BookingDtoFactory;
use Module\Booking\Hotel\Domain;
use Module\Booking\Hotel\Infrastructure;
use Module\Booking\PriceCalculator\Domain\Service\BookingCalculatorInterface;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\BookingCalculator;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

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
            DocumentGeneratorFactory::class,
            fn(ModuleInterface $module) => new DocumentGeneratorFactory(
                $module->config('templates_path'),
                $module->get(FileStorageAdapterInterface::class),
                $module
            )
        );
        $this->app->singleton(
            Domain\Repository\RoomBookingRepositoryInterface::class,
            Infrastructure\Repository\RoomBookingRepository::class,
        );
        $this->app->singleton(
            Domain\Repository\BookingRepositoryInterface::class,
            Infrastructure\Repository\BookingRepository::class
        );
        $this->app->singleton(
            BookingCalculatorInterface::class,
            BookingCalculator::class,
        );
        $this->app->singleton(
            BookingDtoFactoryInterface::class,
            BookingDtoFactory::class,
        );
//        $this->app->singleton(
//            Domain\Repository\RoomRepositoryInterface::class,
//            Infrastructure\Repository\RoomRepository::class
//        );
    }
}
