<?php

namespace Pkg\Booking\Requesting\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Pkg\Booking\Requesting\Domain\Adapter\AdministratorAdapterInterface;
use Pkg\Booking\Requesting\Domain\Factory\RequestFactoryInterface;
use Pkg\Booking\Requesting\Domain\Repository\RequestRepositoryInterface;
use Pkg\Booking\Requesting\Domain\Service\ChangesStorageInterface;
use Pkg\Booking\Requesting\Infrastructure\Adapter\AdministratorAdapter;
use Pkg\Booking\Requesting\Infrastructure\Repository\RequestRepository;
use Pkg\Booking\Requesting\Infrastructure\Service\ChangesStorage;
use Pkg\Booking\Requesting\Service\ChangeMarkRenderer;
use Pkg\Booking\Requesting\Service\RequestFactory;
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
        $this->app->singleton(RequestFactoryInterface::class, RequestFactory::class);
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->singleton(AdministratorAdapterInterface::class, AdministratorAdapter::class);
        $this->app->singleton(ChangesStorageInterface::class, ChangesStorage::class);
        $this->app->singleton(ChangeMarkRenderer::class);
    }

    protected function registerViews(): void
    {
        View::addNamespace('BookingRequesting', __DIR__ . '/../../resources/views');

        Blade::directive('changemark', function ($expression) {
            $condition = "module('BookingRequesting')->make('" . ChangeMarkRenderer::class . "')->changed({$expression})";

            return "<?php if ($condition) { echo \"<span style='color:red;font-weight: bold;'>\"; } ?>";
        });
        Blade::directive('endchangemark', function () {
            $condition = "module('BookingRequesting')->make('" . ChangeMarkRenderer::class . "')->endChanged()";

            return "<?php if ($condition) { echo \"</span>\"; } ?>";
        });
    }

    protected function registerMigrations(): void
    {
        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }
}
