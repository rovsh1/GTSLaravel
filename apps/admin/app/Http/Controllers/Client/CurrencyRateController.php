<?php

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Reference\City;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Format;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;

class CurrencyRateController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'client-currency-rate';
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('client_id', ['text' => 'Клиент', 'renderer' => fn($r, $v) => $r['client_name']])
            ->text('currency_id', ['text' => 'Валюта', 'renderer' => fn($r, $v) => $r['currency_name']])
            ->number('rate', ['text' => 'Курс', 'format' => 'number'])
            ->text('period', ['text' => 'Период', 'renderer' => fn($r, $t) => Format::period($t)]);
    }

    protected function formFactory(): FormContract
    {
        return Form::select('hotel_ids', [
            'label' => 'Отель',
            'emptyItem' => '',
            'required' => true,
            'multiple' => true,
            'groupIndex' => 'city_id',
            'groups' => City::whereHasHotel()->get(),
            'items' => Hotel::all()
        ])
            ->client('client_id', ['label' => 'Клиент', 'emptyItem' => '', 'required' => true])
            ->currency('currency_id', ['label' => 'Валюта', 'required' => true, 'emptyItem' => ''])
            ->number('rate', ['label' => 'Курс', 'required' => true])
            ->dateRange('period', ['label' => 'Период действия', 'required' => true]);
    }

    private function searchForm()
    {
        return (new SearchForm())
            ->currency('currency_id', ['label' => __('label.currency'), 'emptyItem' => ''])
            ->dateRange('start_period', ['label' => 'Дата начала'])
            ->dateRange('end_period', ['label' => 'Дата завершения']);
    }
}
