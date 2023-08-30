<?php

namespace Module\Booking\HotelBooking\Providers;

use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Common\Domain\Factory\DocumentGeneratorFactory;
use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Service\DocumentGenerator\InvoiceGenerator;
use Module\Booking\HotelBooking\Domain\Service\DocumentGenerator\VoucherGenerator;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class DocumentGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \View::addLocation($this->app->config('templates_path'));

        $this->app->singleton(
            DocumentGeneratorFactory::class,
            fn(ModuleInterface $module) => new DocumentGeneratorFactory(
                $module->get(FileStorageAdapterInterface::class),
                $module
            )
//            fn(ModuleInterface $module) => $module->make(DocumentGeneratorFactory::class, [
//                'templatesPath' => $module->config('templates_path'),
//            ])
        );
//        $this->app->singleton(
//            VoucherGenerator::class,
//            fn(ModuleInterface $module) => new VoucherGenerator(
//                $module->config('templates_path'),
//                $module->get(FileStorageAdapterInterface::class),
//                $module->get(HotelAdapterInterface::class),
//                $module->get(AdministratorAdapterInterface::class),
//                $module->get(StatusStorage::class),
//            )
//        );
//        $this->app->singleton(
//            InvoiceGenerator::class,
//            fn(ModuleInterface $module) => new InvoiceGenerator(
//                $module->config('templates_path'),
//                $module->get(FileStorageAdapterInterface::class),
//            )
//        );
    }
}
