<?php

namespace App\Admin\Services\Acl;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AclServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AccessControl::class, function () {
            $resources = new ResourcesCollection();
            $resourceLoader = new Support\ResourceLoader(resource_path('acl'));
            foreach ($resourceLoader->loadResources() as $resource) {
                $resources->add($resource);
            }
            return new AccessControl($resources);
        });
        $this->app->alias(AccessControl::class, 'acl');

        $this->app->bind('acl.resources', fn() => app('acl')->resources());

        $this->app->singleton(Routing\Router::class, fn() => new Routing\Router(app('acl')));
        $this->app->alias(Routing\Router::class, 'acl.router');
    }

    public function boot()
    {
        $this->registerBladeDirectives();
    }

    private function registerBladeDirectives()
    {
        // role
        Blade::directive('role', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->hasRole({$expression})): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        // permission
        Blade::directive('permission', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->hasPermission({$expression})): ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });
    }
}
