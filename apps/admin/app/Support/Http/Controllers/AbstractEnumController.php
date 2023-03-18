<?php

namespace App\Admin\Support\Http\Controllers;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

abstract class AbstractEnumController extends AbstractPrototypeController
{
    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->localeText('name', ['label' => 'Наименование', 'required' => true]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->orderBy('name', 'asc');
    }
}
