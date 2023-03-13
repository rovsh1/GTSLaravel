<?php

namespace App\Admin\Http\Controllers\Reference;

use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Grid\Grid;

class CountryController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'country';
    }

    protected function formFactory()
    {
        return (new Form('data'))
            //->view('default.form')
            ->localeText('name', ['label' => 'Наименование', 'required' => true])
            ->language('language', ['label' => 'Язык', 'emptyItem' => '-Не выбрано-'])
            ->text('flag', ['label' => 'Код флага', 'required' => true])
            ->text('phone_code', ['label' => 'Код телефона', 'required' => true])
            ->currency('currency_id', ['label' => 'Валюта'])
            ->checkbox('default', ['label' => 'По умолчанию']);
    }

    protected function gridFactory()
    {
        return (new Grid())
            ->enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit(['route' => $this->prototype->routeName('edit')])
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
