<?php

namespace App\Shared\Support\Module\Monolith;

use Module\Booking\Shared\Infrastructure\Support\ModuleContext;
use Sdk\Module\Contracts\ContextInterface;
use Sdk\Module\Support\ServiceProvider;

class CommonServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(ContextInterface::class, ModuleContext::class);
    }

}