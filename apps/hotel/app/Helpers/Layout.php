<?php

namespace App\Hotel\Helpers;

use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout as LayoutFacade;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Facades\Sitemap;
use Illuminate\Contracts\View\View;

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

    public static function sitemap(): string|View
    {
        return Sitemap::render();
    }

    public static function bodyClass(): string
    {
        $cls = [];
        $cls[] = Sidebar::isExpanded() ? 'sitemap-expanded' : '';

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
