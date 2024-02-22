<?php

namespace GTS\Hotel\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

//use Ustabor\Request\Domain\Factory\RequestFactoryInterface;
//use Ustabor\Request\Domain\Repository\RequestRepositoryInterface;
//use Ustabor\Request\Domain\Repository\ServiceRepositoryInterface;
//use Ustabor\Request\Infrastructure\Api;
//use Ustabor\Request\Infrastructure\Factory\RequestFactory;
//use Ustabor\Request\Infrastructure\Repository\RequestRepository;
//use Ustabor\Request\Infrastructure\Repository\ServiceRepository;
//use Ustabor\Shared\Domain\Event\DomainEventDispatcherInterface;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //$this->loadMigrationsFrom(__DIR__ . '/../Infrastructure/Database/Migrations');
    }

    public function register()
    {
        //$this->app->register(EventServiceProvider::class);
        //$this->app->addDeferredServices([EventServiceProvider::class]);

        //$this->registerInterfaces();
    }

//    private function registerInterfaces()
//    {
//        $this->app->singleton(Api\Registration\ApiInterface::class, Api\Registration\Api::class);
//        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
//        $this->app->singleton(RequestFactoryInterface::class, RequestFactory::class);
//
//        $this->app->singleton(Api\Services\ApiInterface::class, Api\Services\Api::class);
//        $this->app->singleton(ServiceRepositoryInterface::class, ServiceRepository::class);
//    }
}
