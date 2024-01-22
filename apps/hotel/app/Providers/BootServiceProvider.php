<?php

namespace App\Hotel\Providers;

use App\Hotel\Services\HotelService;
use App\Hotel\Support\Context\ContextManager;
use Illuminate\Support\ServiceProvider;
use Sdk\Shared\Contracts\Context\ContextInterface;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(FormatServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
        $this->app->singleton(ContextInterface::class, ContextManager::class);
    }

    public function boot(): void
    {
        $this->app->singleton(HotelService::class);
    }
}
