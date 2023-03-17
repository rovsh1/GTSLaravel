<?php

namespace App\Admin\Support\Http\Controllers;

use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Grid\Grid;

abstract class AbstractEnumController extends AbstractPrototypeController
{
    protected function formFactory(): Form
    {
        return (new Form('data'))
            ->localeText('name', ['label' => 'Наименование', 'required' => true]);
    }

    protected function gridFactory(): Grid
    {
        return (new Grid())
            ->enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->orderBy('name', 'asc');
    }
}
