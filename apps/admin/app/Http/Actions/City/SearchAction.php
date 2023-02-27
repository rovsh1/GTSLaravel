<?php

namespace App\Admin\Http\Actions\City;

use App\Admin\Models\Country;
use App\Admin\Models\City;
use App\Admin\Support\Http\AbstractSearchAction;

class SearchAction extends AbstractSearchAction
{
    protected $model = City::class;
    protected $title = 'Города';
    protected $view = 'reference.city.index';

    protected function boot()
    {
        $this->enableQuicksearch();
    }

    protected function gridFactory()
    {
        $countries = Country::all()->pluck('name', 'id');

        return parent::gridFactory()
            ->text('id', ['text' => 'ID', 'order' => true])
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('country_id', ['text' => 'Страна', 'renderer' => function ($row) use ($countries) {
                return $countries->has($row->country_id) ? $countries->get($row->country_id) : '-';
            }])
            ->actions('actions', ['route' => route('city.index')])
            ->orderBy('name', 'asc');
    }
}
