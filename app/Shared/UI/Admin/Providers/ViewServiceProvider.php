<?php

namespace GTS\Shared\UI\Admin\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Gsdk\Form as FormNamespace;
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
        FormNamespace\Form::registerNamespace('GTS\Shared\UI\Admin\View\Form\Element');
        FormNamespace\Label::setDefaults([
            'class' => 'col-sm-4 col-form-label'
        ]);
        FormNamespace\Form::setElementDefaults([
            'view' => 'default.form.field',
            'class' => 'form-control'
        ]);
        FormNamespace\Element\Checkbox::setDefaults([
            'class' => 'form-check-input'
        ]);
        FormNamespace\Element\Radio::setDefaults([
            'itemClass' => 'form-check form-check-inline',
            'inputClass' => 'form-check-input',
            'labelClass' => 'form-check-label'
        ]);
        FormNamespace\Element\Range::setDefaults([
            'class' => 'form-range'
        ]);
        FormNamespace\Form::setDefaultMessages([
            'required' => 'The :attribute field is required.',
        ]);
    }
}
