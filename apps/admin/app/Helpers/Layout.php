<?php

namespace App\Admin\Helpers;

use Gsdk\Meta\Meta;

class Layout
{
    public static function meta(): string
    {
        return Meta::render();
    }

    public static function breadcrumbs()
    {
        return app('breadcrumbs')->render();
    }

    public static function sitemap()
    {
        return app('sitemap')->render();
    }

    public static function sidebar()
    {
        return app('sidebar')->render();
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
