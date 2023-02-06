<?php

namespace GTS\Shared\UI\Admin\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::share('menuData', [
            json_decode(
                file_get_contents(base_path('resources/admin/views/menu/menu.json'))
            )
        ]);
    }
}
