<?php

namespace App\Admin\Http\Actions\Country;

use App\Admin\Models\Country;
use App\Admin\Support\Http\AbstractSearchAction;

class SearchAction extends AbstractSearchAction
{
    protected $model = Country::class;
    protected $title = 'Страны';
    protected $view = 'reference.country.index';

    protected function boot()
    {
        $this->enableQuicksearch();
    }

    protected function gridFactory()
    {
        return parent::gridFactory()
            ->text('id', ['text' => 'ID', 'order' => true])
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('phone_code', ['text' => 'Код телефона'])
            ->text('default', ['text' => 'Основная', 'renderer' => function ($row) {
                return $row->default ? 'Да' : 'Нет';
            }])
            ->actions('actions', ['route' => route('country.index')])
            ->orderBy('name', 'asc');
    }
}
