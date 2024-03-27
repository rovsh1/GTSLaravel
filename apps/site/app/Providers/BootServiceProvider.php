<?php

namespace App\Site\Providers;

use App\Site\Support\Context\ContextManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;
use Sdk\Shared\Contracts\Context\ContextInterface;

class BootServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
        $this->app->singleton(ContextInterface::class, ContextManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Cookie::get('lang') !== null) {
            App::setLocale(Cookie::get('lang'));
        }
    }
}
