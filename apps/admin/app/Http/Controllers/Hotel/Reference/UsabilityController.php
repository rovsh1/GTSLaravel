<?php

namespace App\Admin\Http\Controllers\Hotel\Reference;

use App\Admin\Models\Hotel\Reference\UsabilityGroup;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid;

class UsabilityController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel-usability';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->select('group_id', [
                'label' => 'Группа',
                'required' => true,
                'emptyItem' => '',
                'items' => UsabilityGroup::get()
            ])
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->checkbox('popular', ['label' => 'Популярное']);
    }

    protected function gridFactory(): Grid
    {
        return (new Grid())
            ->enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->boolean('popular', ['text' => 'Популярное'])
            ->text('name', ['text' => 'Наименование'])
            ->text('group_name', ['text' => 'Группа', 'order' => true])
            ->orderBy('name', 'asc');
    }
}
