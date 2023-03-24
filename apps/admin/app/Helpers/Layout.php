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
        $cls[] = Sidebar::isExpanded() ? 'sidebar-expanded' : '';
        return implode(' ', $cls);
    }

    public static function sidebar(): string
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

    public static function title(): string
    {
        $html = '<div class="title-wrapper">';
        $html .= '<div class="title">' . Meta::getTitle() . '</div>';
        $html .= self::actions();
        $html .= '</div>';
        return $html;
    }

    public static function buttonBack()
    {
        //app('back')->url();
    }
}
