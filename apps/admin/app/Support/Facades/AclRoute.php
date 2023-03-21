<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\Components\Acl\RouteResourceRegistrar for (string $resource)
 * @method static \App\Admin\Components\Acl\RouteResourceRegistrar resource(string $key, string $controller, array $options = [])
 * @method static void assignRoute(string $routeName, string $permissionSlug)
 * @method static bool isRouteAssigned(string $route)
 * @method static string|null getRoutePermission(string $route)
 *
 * @see \App\Admin\Components\Acl\Router
 */
class AclRoute extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'acl.router';
    }
}
