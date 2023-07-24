<?php

namespace Module\Booking\HotelBooking\Providers;

use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Common\Domain\Factory\DocumentGeneratorFactory;
use Module\Booking\HotelBooking\Domain\Service\DocumentGenerator\InvoiceGenerator;
use Module\Booking\HotelBooking\Domain\Service\DocumentGenerator\VoucherGenerator;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class DocumentGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(
            DocumentGeneratorFactory::class,
            fn(ModuleInterface $module) => new DocumentGeneratorFactory(
                $module->config('templates_path'),
                $module->get(FileStorageAdapterInterface::class),
                $module
            )
        );
        $this->app->singleton(
            VoucherGenerator::class,
            fn(ModuleInterface $module) => $module->make(VoucherGenerator::class, [
                'templatesPath' => $module->config('templates_path')
            ])
            //TODO replace to make

//                return new VoucherGenerator(
//                    $module->config('templates_path'),
//                    $module->get(FileStorageAdapterInterface::class),
//                    $module->get(HotelAdapterInterface::class),
//                    $module->get(AdministratorAdapterInterface::class),
//                    $module->get(StatusStorage::class),
//                );
        );
        $this->app->singleton(
            InvoiceGenerator::class,
            function (ModuleInterface $module) {
                return new InvoiceGenerator(
                    $module->config('templates_path'),
                    $module->get(FileStorageAdapterInterface::class),
                );
            }
        );
    }
}
