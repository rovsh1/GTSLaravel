<?php

namespace App\Core\Providers;

use Module\Shared\Domain\Adapter\ConstantAdapterInterface;
use Module\Shared\Domain\Service\SafeExecutorInterface;
use Module\Shared\Domain\Service\SerializerInterface;
use Module\Shared\Domain\Service\TranslatorInterface;
use Module\Shared\Infrastructure\Adapter\ConstantAdapter;
use Module\Shared\Infrastructure\Service\JsonSerializer;
use Module\Shared\Infrastructure\Service\TransactionalExecutor;
use Module\Shared\Infrastructure\Service\Translator;
use Module\Shared;
use Module\SharedKernel;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(TranslatorInterface::class, Translator::class);
        $this->app->singleton(SerializerInterface::class, JsonSerializer::class);
        $this->app->singleton(SafeExecutorInterface::class, TransactionalExecutor::class);
        $this->app->singleton(ConstantAdapterInterface::class, ConstantAdapter::class);

        //@todo переместить в другое место
        $this->app->register(Shared\Providers\BootServiceProvider::class);

        $kernel = new SharedKernel($this->app, $this->app->modules());
        $this->app->instance(SharedKernel::class, $kernel);
        $kernel->boot();
    }
}
