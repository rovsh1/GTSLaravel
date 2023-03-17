<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid;

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
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->checkbox('in_city', ['label' => 'В пределах города']);
    }

    protected function gridFactory(): Grid
    {
        return (new Grid())
            ->paginator(self::GRID_LIMIT)
            ->enableQuicksearch()
            ->edit($this->prototype)
            ->text('name', ['text' => 'Наименование'])
            ->boolean('in_city', ['text' => 'В пределах города'])
            ->orderBy('name', 'asc');
    }
}
