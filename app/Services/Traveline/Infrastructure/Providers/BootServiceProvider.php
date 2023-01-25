<?php

namespace GTS\Services\Traveline\Infrastructure\Providers;

use GTS\Services\Traveline\Interface\Site\Http\Middleware\VerifyCsrfToken;
use GTS\Shared\Interface\Common\Http\Middleware\VerifyCsrfToken as SharedVerifyCsrfToken;
use Illuminate\Support\ServiceProvider;


class BootServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(SharedVerifyCsrfToken::class, VerifyCsrfToken::class);
    }

    public function boot()
    {

    }

}
