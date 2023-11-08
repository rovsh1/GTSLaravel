<?php

namespace Module\Booking\Requesting\Providers;

use Illuminate\Support\Facades\View;
use Module\Booking\Requesting\Domain\BookingRequest\Adapter\AdministratorAdapterInterface;
use Module\Booking\Requesting\Domain\BookingRequest\Factory\RequestFactory;
use Module\Booking\Requesting\Domain\BookingRequest\Repository\RequestRepositoryInterface;
use Module\Booking\Requesting\Domain\BookingRequest\Service\TemplateCompilerInterface;
use Module\Booking\Requesting\Infrastructure\Adapter\AdministratorAdapter;
use Module\Booking\Requesting\Infrastructure\Repository\RequestRepository;
use Module\Booking\Requesting\Infrastructure\Service\RequestTemplateCompiler;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::addLocation(base_path('resources/pdf-templates'));

        $this->app->singleton(RequestFactory::class);
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->singleton(TemplateCompilerInterface::class, RequestTemplateCompiler::class);
        $this->app->singleton(AdministratorAdapterInterface::class, AdministratorAdapter::class);
    }
}
