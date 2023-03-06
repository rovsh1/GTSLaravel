<?php

namespace App\Admin\Providers;

use App\Admin\Components\Acl\AccessControl;
use App\Admin\Components\Acl\Resource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AclServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AccessControl::class, function () {
            $acl = new AccessControl();
            $this->registerResources($acl->resources());
            return $acl;
        });
        $this->app->alias(AccessControl::class, 'acl');

        $this->app->bind('acl.router', fn() => app('acl')->router());
    }

    public function boot()
    {
        $this->registerBladeDirectives();
    }

    private function registerResources($resources): void
    {
        foreach (app('prototypes')->all() as $prototype) {
            $resources->add(new Resource($prototype->config()));
        }
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
