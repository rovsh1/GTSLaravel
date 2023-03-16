<?php

namespace App\Admin\Providers;

use App\Admin\Helpers;
use App\Admin\Support\View as ViewNamespace;
use App\Admin\Support\View\Grid as GridNamespace;
use App\Admin\View\Components;
use Gsdk\Form as FormNamespace;
use Gsdk\Meta\MetaServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerLayout();
        $this->registerGrid();
        $this->registerForm();
        $this->registerUi();
        $this->registerHelpers();
        $this->registerComponents();
    }

    public function boot() {}

    private function registerGrid()
    {
        GridNamespace\Grid::registerNamespace(GridNamespace::class . '\\Column');
        GridNamespace\Grid::setDefaults([
            'emptyText' => 'Записи отсутствуют'
        ]);
    }

    private function registerForm()
    {
        FormNamespace\Form::registerNamespace('App\Admin\Support\View\Form\Element');
        FormNamespace\Form::setElementDefaults([
            //'view' => 'layouts.ui.form.field'
        ]);
        FormNamespace\Label::setDefaults([
            'class' => 'col-sm-4 col-form-label'
        ]);
        FormNamespace\Form::setElementDefaults([
            'view' => 'layouts.ui.form.field',
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

        $this->app->bind('form', fn($app) => new ViewNamespace\Form\Form('data'));

        $this->app->singleton('sitemap', ViewNamespace\Sitemap\Sitemap::class);

        $this->app->singleton('sidebar', ViewNamespace\Sidebar\Sidebar::class);

        $this->app->singleton('breadcrumbs', ViewNamespace\Navigation\Breadcrumbs::class);
    }

    private function registerUi()
    {
        ViewNamespace\Navigation\Paginator::setDefaults([
            'step' => 20,
            'pagesStep' => 4,
            'prevText' => 'Назад',
            'nextText' => 'Вперед',
            'view' => 'layouts.ui.paginator'
        ]);

        $this->app->singleton('menu.actions', ViewNamespace\Navigation\ActionsMenu::class);
    }

    private function registerHelpers() {}

    private function registerComponents()
    {
        Blade::component('icon', Components\Icon::class);
        Blade::component('category-icon', Components\CategoryIcon::class);
        Blade::component('tab', Components\Tab::class);
        Blade::component('file-image', Components\FileImage::class);
        //Blade::componentNamespace('App\\Admin\\Views\\Components', 'admin');
    }
}
