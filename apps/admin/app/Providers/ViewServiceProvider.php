<?php

namespace App\Admin\Providers;

use App\Admin\Helpers;
use App\Admin\View as ViewNamespace;
use App\Admin\View\Grid as GridNamespace;
use Gsdk\Form as FormNamespace;
use Gsdk\Meta\MetaServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerLayout();
        $this->registerGrid();
        $this->registerForm();
        $this->registerHelpers();
        $this->registerComponents();
    }

    public function boot() {}

    private function registerGrid()
    {
        GridNamespace\Grid::registerNamespace(GridNamespace::class . '\\Column');
    }

    private function registerForm()
    {
        FormNamespace\Form::registerNamespace('App\Admin\View\Form\Element');
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

    private function registerLayout()
    {
        $this->app->register(MetaServiceProvider::class);

        $this->app->singleton('layout', ViewNamespace\Layout::class);
        class_alias(Helpers\Layout::class, 'Layout');

        $this->app->singleton('sidebar', ViewNamespace\Navigation\Sidebar::class);

        $this->app->singleton('breadcrumbs', ViewNamespace\Navigation\Breadcrumbs::class);
    }

    private function registerHelpers() {}

    private function registerComponents()
    {
        //Blade::componentNamespace('App\\Admin\\Views\\Components', 'admin');
    }
}
