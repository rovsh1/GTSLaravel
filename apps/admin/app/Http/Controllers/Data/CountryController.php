<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

class CountryController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'country';
    }

    protected function formFactory(): FormContract
    {
        return Form::localeText('name', ['label' => 'Наименование', 'required' => true])
            //->view('default.form')
            ->language('language', ['label' => 'Язык по-умолчанию', 'emptyItem' => '-Не выбрано-'])
            ->text('flag', ['label' => 'Код флага', 'maxlength' => 2, 'required' => true])
            ->text('phone_code', ['label' => 'Код телефона', 'maxlength' => 8, 'required' => true])
            ->currency('currency_id', ['label' => 'Валюта'])
            ->checkbox('default', ['label' => 'По умолчанию']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            //->id('id', ['text' => 'ID', 'order' => true])
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('phone_code', ['text' => 'Код телефона'])
            ->text('default', [
                'text' => 'Основная',
                'renderer' => fn($row) => $row->default ? 'Да' : 'Нет'
            ])
            //->actions(['route' => $this->prototype->route('index')])
            ->orderBy('name', 'asc');
    }
}
