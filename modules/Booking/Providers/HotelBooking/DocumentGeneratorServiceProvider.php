<?php

namespace Module\Booking\Providers\HotelBooking;

use Illuminate\Support\Facades\View;
use Module\Booking\Domain\Shared\Factory\DocumentGeneratorFactory;
use Sdk\Module\Support\ServiceProvider;

class DocumentGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::addLocation($this->app->config('templates_path'));

        $this->app->singleton(
            DocumentGeneratorFactory::class
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
