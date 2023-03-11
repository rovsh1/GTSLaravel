<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\Components\Acl\Permissions permissions()
 * @method static bool isSuperuser()
 * @method static bool isAllowed(string $resource, string $permission = null)
 * @method static bool isRouteAssigned(string $route)
 * @method static bool isRouteAllowed(string $route)
 *
 * @see \App\Admin\Components\Acl\AccessControl
 */
class Acl extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'acl';
    }
}
