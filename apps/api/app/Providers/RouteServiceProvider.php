<?php

namespace App\Api\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        Route::pattern('id', '[0-9]+');

        $this->routes(function () {
            Route::prefix('traveline')
                ->as('traveline.')
                ->group(base_path('routes/traveline.php'));
        });
    }
}
