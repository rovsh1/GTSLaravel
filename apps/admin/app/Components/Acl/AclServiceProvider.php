<?php

namespace App\Admin\Components\Acl;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AclServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AccessControl::class, function () {
            return new AccessControl(app('resources'));
        });
        $this->app->alias(AccessControl::class, 'acl');

        $this->app->bind('acl.router', fn() => app('acl')->router());
    }

    public function boot()
    {
        $this->registerBladeDirectives();
    }

    private function registerBladeDirectives()
    {
        // role
        Blade::directive('role', function ($expression) {
            return "<?php if (app('acl')->hasRole({$expression})): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        // permission
        Blade::directive('permission', function ($expression) {
            return "<?php if (app('acl')->isAllowed({$expression})): ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });
    }
}
