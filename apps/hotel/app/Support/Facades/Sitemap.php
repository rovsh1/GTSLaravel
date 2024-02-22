<?php

namespace App\Hotel\Support\Facades;

use App\Admin\Support\View\Sidebar\Menu\ItemInterface;
use App\Admin\Support\View\Sitemap\CategoryMenu;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Hotel\Support\View\Sitemap\Sitemap currentRoute(string $key)
 * @method static \App\Hotel\Support\View\Sitemap\Sitemap currentCategory(string $category)
 * @method static bool isCurrent(ItemInterface|CategoryMenu $item)
 * @method static string getCurrentRoute()
 * @method static string getCurrentCategory()
 * @method static array getCategories()
 * @method static string render()
 *
 * @see \App\Hotel\Support\View\Sitemap\Sitemap
 */
class Sitemap extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sitemap';
    }
}
