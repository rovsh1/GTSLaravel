<?php

namespace App\Core\Providers;

use Module\Shared\Domain\Service\SerializerInterface;
use Module\Shared\Domain\Service\TranslatorInterface;
use Module\Shared\Infrastructure\Service\JsonSerializer;
use Module\Shared\Infrastructure\Service\Translator;
use Module\SharedKernel;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(TranslatorInterface::class, Translator::class);
        $this->app->singleton(SerializerInterface::class, JsonSerializer::class);

        $kernel = new SharedKernel($this->app, $this->app->modules());
        $this->app->instance(SharedKernel::class, $kernel);
        $kernel->boot();
    }
}
