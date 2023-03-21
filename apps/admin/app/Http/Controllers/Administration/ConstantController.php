<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

class ConstantController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'constant';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->addElement('value', 'text', ['label' => 'Значение'])
            ->addElement('enabled', 'checkbox', ['label' => 'Enabled']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Расшифровка', 'order' => true])
            ->text('key', ['text' => 'Ключ', 'order' => true])
            ->text('value', ['text' => 'Значение'])
            ->boolean('enabled', ['text' => 'Enabled', 'order' => true]);
    }
}
