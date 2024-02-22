<?php

namespace App\Hotel\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        Route::pattern('id', '[0-9]+');

        $this->routes(function () {
            Route::middleware(['web', 'hotel'])
                ->group(package_path('routes/boot.php'));
        });
    }
}
