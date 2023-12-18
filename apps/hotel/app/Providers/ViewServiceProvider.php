<?php

namespace App\Hotel\Providers;

use App\Hotel\Support\Facades\Meta;
use App\Hotel\View\Components;
use App\Hotel\Support\View as ViewNamespace;
use App\Hotel\Support\View\Grid as GridNamespace;
use Gsdk\Form as FormNamespace;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::addLocation(package_path('resources/views'));

        $this->registerLayout();
        $this->registerGrid();
        $this->registerForm();
        $this->registerUi();
        $this->registerComponents();
    }

    private function registerGrid(): void
    {
        GridNamespace\GridBuilder::registerNamespace(GridNamespace::class . '\\Column');
        GridNamespace\GridBuilder::setDefaults([
            'emptyText' => 'Записи отсутствуют'
        ]);
    }

    private function registerForm(): void
    {
        FormNamespace\Form::setDefaults(['name' => 'data']);
        FormNamespace\Form::registerNamespace('App\Hotel\Support\View\Form\Element');
        FormNamespace\Form::setElementDefaults([
            //'view' => 'layouts.ui.form.field'
        ]);
        FormNamespace\Label::setDefaults([
            'class' => 'col-sm-5 col-form-label'
        ]);
        FormNamespace\Form::setElementDefaults([
            'view' => 'layouts.ui.form.field',
            'class' => 'form-control'
        ]);
        FormNamespace\Element\Checkbox::setDefaults([
            'class' => 'form-check-input'
        ]);
        FormNamespace\Element\Select::setDefaults([
            'class' => 'form-select-element'
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

    private function registerLayout(): void
    {
        class_alias(Meta::class, 'Meta');

//        $this->app->singleton('sitemap', ViewNamespace\Sitemap\Sitemap::class);
//
//        $this->app->singleton('sidebar', ViewNamespace\Sidebar\Sidebar::class);
//
//        $this->app->singleton('breadcrumbs', ViewNamespace\Navigation\Breadcrumbs::class);
    }

    private function registerUi(): void
    {
//        ViewNamespace\Navigation\Paginator::setDefaults([
//            'step' => 20,
//            'pagesStep' => 4,
//            'prevText' => 'Назад',
//            'nextText' => 'Вперед',
//            'view' => 'layouts.ui.paginator'
//        ]);
    }

    private function registerComponents(): void {
        Blade::component('icon', Components\Icon::class);
        Blade::component('user-avatar', Components\UserAvatar::class);
    }
}
