<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\Support\View\Navigation\Sidebar currentRoute(string $key)
 * @method static \App\Admin\Support\View\Navigation\Sidebar currentCategory(string $category)
 * @method static string render()
 *
 * @see \App\Admin\Support\View\Navigation\Sidebar
 */
class Sidebar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sidebar';
    }
}
