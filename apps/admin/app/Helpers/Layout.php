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
        return view('layouts/dashboard/sidebar');
    }
}
