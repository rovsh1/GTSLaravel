<?php

namespace App\Admin\Http\Controllers\Reference;

use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Grid\Grid;

class CityController extends AbstractPrototypeController
{
    protected $prototype = 'reference.city';

    protected function formFactory()
    {
        return (new Form('data'))
            ->csrf()
            ->country('country_id', ['label' => 'Страна', 'required' => true])
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->textarea('text', ['label' => 'Описание']);
    }

    protected function gridFactory()
    {
        return (new Grid())
            ->enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->text('id', ['text' => 'ID', 'order' => true])
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('country_name', ['text' => 'Страна'])
            ->actions('actions', ['route' => $this->prototype->route('index')])
            ->orderBy('name', 'asc');
    }
}
