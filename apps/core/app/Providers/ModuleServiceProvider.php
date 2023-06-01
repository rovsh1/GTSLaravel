<?php

namespace App\Core\Providers;

use Module\Shared\Domain\Service\SerializerInterface;
use Module\Shared\Domain\Service\TranslatorInterface;
use Module\Shared\Infrastructure\Service\JsonSerializer;
use Module\Shared\Infrastructure\Service\Translator;
use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;
use Sdk\Module\Foundation\Providers\ModuleServiceProvider as ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    protected array $sharedBindings = [
        PortGatewayInterface::class,
        //ModulesBusInterface::class
        TranslatorInterface::class,
        SerializerInterface::class,
    ];

    public function boot()
    {
        $this->app->singleton(TranslatorInterface::class, Translator::class);
        $this->app->singleton(SerializerInterface::class, JsonSerializer::class);
        $this->registerModulesDependencies($this->app->modules());
    }
}
