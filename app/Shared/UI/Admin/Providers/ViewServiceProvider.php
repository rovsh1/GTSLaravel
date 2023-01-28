<?php

namespace GTS\Shared\UI\Admin\Providers;

use GTS\Shared\UI\Admin\View\Grid as GridNamespace;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use GTS\Shared\UI\Admin\View\Layout;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('layout', Layout::class);

        //$this->app->singleton('sidebar', Sidebar::class);

        View::addLocation(resource_path('admin/views'));
        //$this->app->singleton('breadcrumbs', function () { return app('layout')->menu('breadcrumbs'); });

        GridNamespace\Grid::registerNamespace(GridNamespace::class . '\\Column');
    }
}
