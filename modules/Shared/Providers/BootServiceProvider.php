<?php

namespace Module\Shared\Providers;

use Illuminate\Support\ServiceProvider;

use Module\Shared\Domain\Repository\ConstantRepositoryInterface;
use Module\Shared\Infrastructure\Repository\ConstantRepository;

class BootServiceProvider extends ServiceProvider
{
    public function boot() {}

    public function register()
    {
        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
        $this->app->singleton(ConstantRepositoryInterface::class, ConstantRepository::class);
    }
}
