<?php

namespace Module\Booking\Requesting\Providers;

use Illuminate\Support\Facades\View;
use Module\Booking\Requesting\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Requesting\Domain\Factory\RequestFactory;
use Module\Booking\Requesting\Domain\Repository\RequestRepositoryInterface;
use Module\Booking\Requesting\Domain\Service\ChangesStorageInterface;
use Module\Booking\Requesting\Infrastructure\Adapter\AdministratorAdapter;
use Module\Booking\Requesting\Infrastructure\Repository\RequestRepository;
use Module\Booking\Requesting\Infrastructure\Service\ChangesStorage;
use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(DomainEventServiceProvider::class);
        $this->app->register(IntegrationEventServiceProvider::class);
        $this->app->register(SharedBookingServiceProvider::class);
    }

    public function boot(): void
    {
        View::addLocation(base_path('resources/pdf-templates'));

        $this->app->singleton(RequestFactory::class);
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->singleton(AdministratorAdapterInterface::class, AdministratorAdapter::class);
        $this->app->singleton(ChangesStorageInterface::class, ChangesStorage::class);
    }
}
