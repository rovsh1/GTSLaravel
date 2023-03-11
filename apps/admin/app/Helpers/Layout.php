<?php

namespace App\Admin\Helpers;

use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Facades\Sitemap;
use Gsdk\Meta\Meta;

class Layout
{
    public static function meta(): string
    {
        return Meta::render();
    }

    public static function breadcrumbs()
    {
        return Breadcrumb::render();
    }

    public static function sitemap()
    {
        return Sitemap::render();
    }

    public static function sidebar()
    {
        return Sidebar::render();
    }

    public static function actions()
    {
        if (app()->resolved('menu.actions')) {
            return app('menu.actions')->render();
        } else {
            return '';
        }
    }

    public static function buttonBack()
    {
        //app('back')->url();
    }
}
