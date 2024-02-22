<?php

namespace App\Admin\Http\Controllers\Hotel\Reference;

use App\Admin\Models\Hotel\Reference\ServiceType;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

class ServiceController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel-service';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->select('type_id', [
                'label' => 'Категория',
                'required' => true,
                'emptyItem' => '',
                'items' => ServiceType::get()
            ])
            ->localeText('name', ['label' => 'Наименование', 'required' => true]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('type_name', ['text' => 'Категория', 'order' => true])
            ->orderBy('name', 'asc');
    }
}
