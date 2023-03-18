<?php

namespace App\Admin\Providers;

use App\Admin\Support\Facades\AclRoute;
use App\Admin\Support\Facades\Prototypes;
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
            $routeRegistrar = Route::middleware(['web', 'admin'])
                ->group(base_path('routes/boot.php'));

            $this->registerPrototypesRoutes();

            app('acl.router')->registerRoutes($routeRegistrar);
        });
    }

    private function registerPrototypesRoutes()
    {
        foreach (Prototypes::all() as $prototype) {
            AclRoute::resource($prototype->key, $prototype->config('controller'), $prototype->config('routeOptions') ?? []);
        }
    }
}
