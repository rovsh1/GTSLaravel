<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static $this add(array $params)
 * @method static $this addUrl(string $url, array|string $params)
 * @method static $this addRoute(string $route, array|string $params)
 * @method static $this hr()
 * @method static bool isEmpty()
 * @method static string render()
 *
 * @see \App\Admin\Support\View\Sidebar\Sidebar
 */
class ActionsMenu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'menu.actions';
    }
}
