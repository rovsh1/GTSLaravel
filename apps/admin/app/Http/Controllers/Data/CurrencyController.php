<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

class CurrencyController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'currency';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->number('code_num', ['label' => 'Код (цифровой)', 'required' => true])
            ->text('code_char', ['label' => 'Код (символьный)', 'required' => true])
            ->text('sign', ['label' => 'Символ', 'required' => true]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            //->id('id', ['text' => 'ID', 'order' => true])
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('code_num', ['text' => 'Код (цифровой)'])
            ->text('code_char', ['text' => 'Код (символьный)'])
            ->text('sign', ['text' => 'Символ'])
            ->number('rate', ['text' => 'Курс', 'order' => true])
            ->orderBy('id', 'asc');
    }
}
