<?php

namespace App\Admin\Http\Resources\Country;

use App\Admin\Models\Country;
use App\Admin\Support\Http\Resources\AbstractGridResource;
use App\Admin\View\Grid\Grid;

class CountriesResource extends AbstractGridResource
{
    protected string $model = Country::class;

    protected string $title = 'Страны';

    protected string $view = 'reference.country.index';

    protected function boot()
    {
        $this->enableQuicksearch();
    }

    protected function grid(): Grid
    {
        return (new Grid())
            ->text('id', ['text' => 'ID', 'order' => true])
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('phone_code', ['text' => 'Код телефона'])
            ->text('default', [
                'text' => 'Основная',
                'renderer' => fn($row) => $row->default ? 'Да' : 'Нет'
            ])
            ->actions('actions', ['route' => route('reference.country.index')])
            ->orderBy('name', 'asc');
    }
}
