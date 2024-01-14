<?php

namespace App\Shared\Support\Module\Monolith;

use Sdk\Module\Contracts\ContextInterface;
use Sdk\Module\Support\Context\ModuleContext;
use Sdk\Module\Support\ServiceProvider;

class CommonServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(ContextInterface::class, ModuleContext::class);
    }
}