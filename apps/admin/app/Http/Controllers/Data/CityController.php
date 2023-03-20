<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Form\SearchForm;

class CityController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'city';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->country('country_id', ['label' => 'Страна', 'required' => true])
            ->localeText('name', ['label' => 'Наименование', 'required' => true])
            ->textarea('text', ['label' => 'Описание']);
    }

    protected function gridFactory(): GridContract
    {
        $form = (new SearchForm())
            ->country('country_id', ['label' => __('label.country'), 'emptyItem' => __('select-all')]);

        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->setSearchForm($form)
            ->edit($this->prototype)
            //->id('id', ['text' => 'ID', 'order' =
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('country_name', ['text' => 'Страна'])
            //->actions('actions', ['route' => $this->prototype->route('index')])
            ->orderBy('name', 'asc');
    }
}
