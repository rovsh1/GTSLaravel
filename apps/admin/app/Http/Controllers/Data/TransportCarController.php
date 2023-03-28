<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Files\TransportImage;
use App\Admin\Models\Reference\TransportType;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

class TransportCarController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'transport-car';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->select('type_id', [
                'label' => 'Тип',
                'emptyItem' => '',
                'required' => true,
                'items' => TransportType::get()
            ])
            ->text('mark', ['label' => 'Марка', 'required' => true])
            ->text('model', ['label' => 'Модель', 'required' => true])
            ->number('passengers_number', ['label' => 'Максимальное количество мест', 'required' => true])
            ->number('bags_number', ['label' => 'Максимальное количество чемоданов', 'required' => true])
            ->image('image', [
                'label' => 'Фото',
                'fileType' => TransportImage::class
            ]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('type_name', ['text' => 'Тип', 'order' => true])
            ->text('mark', ['text' => 'Марка', 'order' => true])
            ->text('model', ['text' => 'Модель', 'order' => true])
            ->number('passengers_number', ['text' => 'Кол-во пасажиров', 'format' => 'int', 'order' => true])
            ->number('bags_number', ['text' => 'Мест багажа', 'format' => 'int', 'order' => true])
            ->orderBy('mark', 'asc');
    }
}
