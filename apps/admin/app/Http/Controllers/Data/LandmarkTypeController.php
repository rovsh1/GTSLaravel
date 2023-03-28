<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

class LandmarkTypeController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'landmark-type';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->text('alias', ['label' => 'Ключ', 'required' => true])
            ->text('name', ['label' => 'Наименование', 'required' => true]);
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
