<?php

namespace App\Admin\Support\Facades;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Support\View\Navigation\Breadcrumbs;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Breadcrumbs add(array|string $params)
 * @method static Breadcrumbs addUrl(string $url, array|string $params)
 * @method static Breadcrumbs addRoute(string $route, array|string $params)
 * @method static Breadcrumbs prototype(string|Prototype $prototype)
 * @method static string render()
 *
 * @see Breadcrumbs
 */
class Breadcrumb extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'breadcrumbs';
    }
}
