<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\Support\View\Sidebar\Sidebar currentRoute(string $key)
 * @method static \App\Admin\Support\View\Sidebar\Sidebar currentCategory(string $category)
 * @method static void submenu(\App\Admin\Support\View\Sidebar\AbstractSubmenu $menu)
 * @method static bool isExpanded()
 * @method static string render()
 *
 * @see \App\Admin\Support\View\Sidebar\Sidebar
 */
class Sidebar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sidebar';
    }
}
