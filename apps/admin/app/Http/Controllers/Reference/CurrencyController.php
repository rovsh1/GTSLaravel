<?php

namespace App\Admin\Http\Controllers\Reference;

use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Grid\Grid;

class CurrencyController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'currency';
    }

    protected function formFactory()
    {
        return (new Form('data'))
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->number('code_num', ['label' => 'Код (цифровой)', 'required' => true])
            ->text('code_char', ['label' => 'Код (символьный)', 'required' => true])
            ->text('sign', ['label' => 'Символ', 'required' => true]);
    }

    protected function gridFactory()
    {
        return (new Grid())
            ->enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit(['route' => $this->prototype->routeName('edit')])
            //->id('id', ['text' => 'ID', 'order' => true])
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('code_num', ['text' => 'Код (цифровой)'])
            ->text('code_char', ['text' => 'Код (символьный)'])
            ->text('sign', ['text' => 'Символ'])
            ->number('rate', ['text' => 'Курс', 'order' => true])
            ->orderBy('id', 'asc');
    }
}
