<?php

namespace App\Api\Providers;

use App\Api\Support\Context\ContextManager;
use Illuminate\Support\ServiceProvider;
use Pkg\Supplier\Traveline\RouteServiceProvider as TravelineRouteServiceProvider;
use Sdk\Shared\Contracts\Context\ContextInterface;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(TravelineRouteServiceProvider::class);
        $this->app->singleton(ContextInterface::class, ContextManager::class);
    }

    public function boot(): void
    {
        //
    }
}
