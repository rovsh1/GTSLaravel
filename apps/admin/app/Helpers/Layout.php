<?php

namespace App\Admin\Helpers;

use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Facades\Sitemap;
use App\Admin\Support\Facades\Layout as LayoutFacade;

class Layout
{
    public static function meta(): string
    {
        return LayoutFacade::renderMeta();
    }

    public static function breadcrumbs(): string
    {
        return Breadcrumb::render();
    }

    public static function sitemap(): string
    {
        return Sitemap::render();
    }

    public static function bodyClass(): string
    {
        $cls = [];
        $cls[] = Sidebar::isExpanded() ? 'sitemap-disable-animation sitemap-expanded' : '';

        return implode(' ', $cls);
    }

    public static function sidebar(): string
    {
        return Sidebar::render();
    }

    public static function buttonBack()
    {
        //app('back')->url();
    }
}
