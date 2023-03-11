<?php

namespace App\Admin\Support\Facades;

use App\Admin\Support\View\Sitemap\Menu\CategoryMenu;
use App\Admin\Support\View\Sitemap\Menu\ItemInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\Support\View\Sitemap\Sitemap currentRoute(string $key)
 * @method static \App\Admin\Support\View\Sitemap\Sitemap currentCategory(string $category)
 * @method static bool isCurrent(ItemInterface|CategoryMenu $item)
 * @method static string getCurrentRoute()
 * @method static string getCurrentCategory()
 * @method static string render()
 *
 * @see \App\Admin\Support\View\Sitemap\Sitemap
 */
class Sitemap extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sitemap';
    }
}
