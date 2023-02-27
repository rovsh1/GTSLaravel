<?php

namespace App\Admin\Http\Actions\Currency;

use App\Admin\Models\Currency;
use App\Admin\Support\Http\AbstractSearchAction;

class SearchAction extends AbstractSearchAction
{
    protected $model = Currency::class;
    protected $title = 'Валюты';
    protected $view = 'reference.currency.index';

    protected function boot()
    {
        $this->enableQuicksearch();
    }

    protected function gridFactory()
    {
        return parent::gridFactory()
            ->text('id', ['text' => 'ID', 'order' => true])
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('code_num', ['text' => 'Код (цифровой)'])
            ->text('code_char', ['text' => 'Код (символьный)'])
            ->text('sign', ['text' => 'Символ'])
            ->text('rate', ['text' => 'Курс', 'order' => true])
            ->actions('actions', ['route' => route('currency.index')])
            ->orderBy('id', 'asc');
    }
}
