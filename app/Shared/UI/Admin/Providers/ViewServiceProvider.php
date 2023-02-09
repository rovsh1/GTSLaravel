<?php

namespace GTS\Shared\UI\Admin\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use GTS\Shared\UI\Admin\View\Form as FormNamespace;
use GTS\Shared\UI\Admin\View\Grid as GridNamespace;
use GTS\Shared\UI\Admin\View\Layout;
use GTS\Shared\UI\Admin\View\Sidebar\Sidebar;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('layout', Layout::class);

        $this->app->singleton('sidebar', Sidebar::class);

        View::addLocation(resource_path('admin/views'));
        //$this->app->singleton('breadcrumbs', function () { return app('layout')->menu('breadcrumbs'); });

        GridNamespace\Grid::registerNamespace(GridNamespace::class . '\\Column');

        $this->registerForm();
    }

    private function registerForm()
    {
        //        FormNamespace\Form::registerNamespace(FormNamespace::class . '\\Element');
        FormNamespace\Form::setDefaults([
//            'view' => 'default.form.edit'
        ]);
        FormNamespace\Form::setElementDefaults([
            'class' => null
        ]);
        FormNamespace\Form::setDefaultMessages([
            'required' => 'The :attribute field is required.',
        ]);
    }
}
