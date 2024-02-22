<?php

namespace App\Hotel\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Hotel\Support\View\Sidebar\Sidebar currentRoute(string $key)
 * @method static \App\Hotel\Support\View\Sidebar\Sidebar currentCategory(string $category)
 * @method static void submenu(\App\Hotel\Support\View\Sidebar\AbstractSubmenu $menu)
 * @method static bool isExpanded()
 * @method static string render()
 *
 * @see \App\Hotel\Support\View\Sidebar\Sidebar
 */
class Sidebar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sidebar';
    }
}
