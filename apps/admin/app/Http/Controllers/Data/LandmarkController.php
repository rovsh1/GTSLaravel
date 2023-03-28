<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Models\Reference\LandmarkType;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

class LandmarkController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'landmark';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->city('city_id', ['label' => 'Город', 'emptyItem' => '', 'required' => true])
            ->select('type_id', [
                'label' => 'Тип',
                'emptyItem' => '',
                'items' => LandmarkType::get()
            ])
            ->localeText('name', ['label' => 'Наименование', 'required' => true])
            ->text('address', ['label' => 'Адрес', 'required' => true])
            //->text('address_lat', ['label' => 'Адрес', 'required' => true])
            ->checkbox('in_city', ['label' => 'В пределах города']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('city_name', ['text' => 'Город', 'order' => true])
            ->text('type_name', ['text' => 'Тип', 'order' => true])
            ->text('address', ['text' => 'Адрес'])
            ->boolean('in_city', ['text' => 'В пределах города'])
            ->number('center_distance', ['text' => 'Расстояние до центра', 'order' => true])
            ->orderBy('name', 'asc');
    }
}
