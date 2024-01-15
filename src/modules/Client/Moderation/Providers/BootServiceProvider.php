<?php

namespace Module\Client\Moderation\Providers;

use Module\Client\Moderation\Domain;
use Module\Client\Moderation\Infrastructure;
use Module\Client\Shared\Providers\BootServiceProvider as SharedClientServiceProvider;
use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(SharedClientServiceProvider::class);
    }

    public function boot()
    {
        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
        $this->app->singleton(
            Domain\Repository\LegalRepositoryInterface::class,
            Infrastructure\Repository\LegalRepository::class
        );
        $this->app->singleton(
            Domain\Repository\CurrencyRateRepositoryInterface::class,
            Infrastructure\Repository\CurrencyRateRepository::class
        );
        $this->app->singleton(
            Domain\Repository\ContractRepositoryInterface::class,
            Infrastructure\Repository\ContractRepository::class
        );
    }
}
