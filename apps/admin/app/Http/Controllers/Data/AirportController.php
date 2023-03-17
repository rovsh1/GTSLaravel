<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid;

class AirportController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'airport';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->city('city_id', ['label' => 'Город', 'emptyItem' => '', 'required' => true])
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->text('code', ['label' => 'Код', 'required' => true]);
    }

    protected function gridFactory(): Grid
    {
        return (new Grid())
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('city_name', ['text' => 'Город', 'order' => true])
            ->orderBy('name', 'asc');
    }
}
