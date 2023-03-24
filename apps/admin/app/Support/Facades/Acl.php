<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\Components\Acl\Permissions permissions()
 * @method static \App\Admin\Components\Acl\ResourcesCollection resources()
 * @method static \App\Admin\Components\Acl\Resource|null resource(string $name)
 * @method static bool isSuperuser()
 * @method static bool isAllowed(string $resource, string $permission = null)
 * @method static bool isReadAllowed(string $resource)
 * @method static bool isUpdateAllowed(string $resource)
 * @method static bool isCreateAllowed(string $resource)
 * @method static bool isDeleteAllowed(string $resource)
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
