<?php

namespace App\Admin\Http\Controllers\Reservation;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

class TransportTypeController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'transport-type';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->color('color', ['label' => 'Цвет бейджа на сайте']);
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
