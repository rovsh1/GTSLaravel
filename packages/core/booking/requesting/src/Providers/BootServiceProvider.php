<?php

namespace Pkg\Booking\Requesting\Providers;

use Illuminate\Support\Facades\View;
use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Pkg\Booking\Requesting\Domain\Adapter\AdministratorAdapterInterface;
use Pkg\Booking\Requesting\Domain\Factory\RequestFactory;
use Pkg\Booking\Requesting\Domain\Repository\RequestRepositoryInterface;
use Pkg\Booking\Requesting\Domain\Service\ChangesStorageInterface;
use Pkg\Booking\Requesting\Infrastructure\Adapter\AdministratorAdapter;
use Pkg\Booking\Requesting\Infrastructure\Repository\RequestRepository;
use Pkg\Booking\Requesting\Infrastructure\Service\ChangesStorage;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerMigrations();

        $this->app->register(DomainEventServiceProvider::class);
        $this->app->register(IntegrationEventServiceProvider::class);
        $this->app->register(SharedBookingServiceProvider::class);
    }

    public function boot(): void
    {
        $this->registerViews();
        $this->registerServices();
    }

    protected function registerServices(): void
    {
        $this->app->singleton(RequestFactory::class);
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->singleton(AdministratorAdapterInterface::class, AdministratorAdapter::class);
        $this->app->singleton(ChangesStorageInterface::class, ChangesStorage::class);
    }

    protected function registerViews(): void
    {
        View::addNamespace('BookingRequesting', __DIR__ . '/../../resources/views');
    }

    protected function registerMigrations(): void
    {
        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }
}
