<?php

namespace App\Admin\Helpers;

use Gsdk\Meta\Meta;

class Layout
{
    public static function meta(): string
    {
        return Meta::render();
    }

    public static function breadcrumbs(): string
    {
        return app('breadcrumbs')->render();
    }

    public static function sidebar(): string
    {
        //FIXME use sidebar
        return view('layouts/sections/menu/vertical__menu');
        //return app('sidebar')->render();
    }
}
