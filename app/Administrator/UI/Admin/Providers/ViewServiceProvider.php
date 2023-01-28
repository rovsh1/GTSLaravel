<?php

namespace GTS\Administrator\UI\Admin\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use GTS\Administrator\UI\Admin\View\Layout;
use GTS\Administrator\UI\Admin\View\Sidebar\Sidebar;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('layout', Layout::class);

        //$this->app->singleton('sidebar', Sidebar::class);

        View::addLocation(resource_path('admin/views'));
        //$this->app->singleton('breadcrumbs', function () { return app('layout')->menu('breadcrumbs'); });
    }
}
