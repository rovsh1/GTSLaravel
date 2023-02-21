<?php

namespace Module\Hotel\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;

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

        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
        $this->app->singleton(\Module\Hotel\Infrastructure\Facade\RoomQuotaFacadeInterface::class, \Module\Hotel\Infrastructure\Facade\RoomQuotaFacade::class);
        $this->app->singleton(\Module\Hotel\Infrastructure\Facade\RoomPriceFacadeInterface::class, \Module\Hotel\Infrastructure\Facade\RoomPriceFacade::class);
        $this->app->singleton(\Module\Hotel\Infrastructure\Facade\SearchFacadeInterface::class, \Module\Hotel\Infrastructure\Facade\SearchFacade::class);
        $this->app->singleton(\Module\Hotel\Infrastructure\Facade\InfoFacadeInterface::class, \Module\Hotel\Infrastructure\Facade\InfoFacade::class);

        $this->app->singleton(\Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface::class, \Module\Hotel\Infrastructure\Repository\RoomQuotaRepository::class);
        $this->app->singleton(\Module\Hotel\Domain\Repository\RoomPriceRepositoryInterface::class, \Module\Hotel\Infrastructure\Repository\RoomPriceRepository::class);
    }
}
