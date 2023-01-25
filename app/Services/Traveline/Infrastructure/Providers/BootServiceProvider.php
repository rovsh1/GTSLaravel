<?php

namespace GTS\Services\Traveline\Infrastructure\Providers;

use GTS\Services\Traveline\Interface\Api\Http\Middleware\VerifyCsrfToken;
use GTS\Shared\Interface\Common\Http\Middleware\VerifyCsrfToken as SharedVerifyCsrfToken;
use Illuminate\Support\ServiceProvider;


class BootServiceProvider extends ServiceProvider
{

    private $providers = [
        RouteServiceProvider::class,
    ];

    public function register()
    {
        $this->app->bind(SharedVerifyCsrfToken::class, VerifyCsrfToken::class);

        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    public function boot()
    {

    }

}
