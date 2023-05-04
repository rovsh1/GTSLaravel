<?php

namespace Module\Shared\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Shared\Domain\Repository\ConstantRepositoryInterface;
use Module\Shared\Domain\Service\DomainSerializerInterface;
use Module\Shared\Infrastructure\Repository\ConstantRepository;
use Module\Shared\Infrastructure\Service\JsonSerializer;

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
        $this->app->singleton(DomainSerializerInterface::class, JsonSerializer::class);
    }
}
