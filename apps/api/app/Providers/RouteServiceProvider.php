<?php

namespace App\Api\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::pattern('id', '[0-9]+');

        $this->routes(function () {
            Route::prefix('admin')
                ->middleware(HandleCors::class)
                ->as('admin.')
                ->group(base_path('routes/admin.php'));

            Route::prefix('traveline')
                ->as('traveline.')
                ->group(base_path('routes/traveline.php'));
        });
    }
}
