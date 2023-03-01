<?php

namespace App\Admin\Providers;

use App\Admin\Http\View\Grid\Grid;
use App\Admin\Http\View\Sidebar\Sidebar;
use App\Admin\View\Grid as GridNamespace;
use App\Admin\View\Layout;
use Gsdk\Form as FormNamespace;
use Gsdk\Meta\Meta;
use Gsdk\Meta\MetaServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(MetaServiceProvider::class);
        class_alias(Meta::class, 'Meta');

        $this->app->singleton('layout', Layout::class);

        $this->app->singleton('sidebar', Sidebar::class);

        //$this->app->singleton('breadcrumbs', function () { return app('layout')->menu('breadcrumbs'); });

        //View::addLocation(resource_path('admin/views'));

        $this->registerGrid();

        $this->registerForm();
    }

    public function boot() {}

    private function registerGrid()
    {
        Grid::registerNamespace(GridNamespace::class . '\\Column');
    }

    private function registerForm()
    {
        FormNamespace\Form::registerNamespace('App\Admin\Http\View\Form\Element');
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
