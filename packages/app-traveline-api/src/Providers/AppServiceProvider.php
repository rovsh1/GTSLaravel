<?php

namespace Pkg\App\Traveline\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/traveline.php', 'services.traveline');
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void {}
}
