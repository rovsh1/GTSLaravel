<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Enums\Hotel\PriceList\StatusEnum;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid;
use App\Admin\Support\View\Grid\Search;

class PriceListController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel-price-list';
    }

    protected function gridFactory(): Grid
    {
        return (new Grid())
            ->enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->paginator(self::GRID_LIMIT)
            ->text('client_id', ['text' => 'Клиент', 'renderer' => fn($r, $v) => $r['client_name']])
            ->text('currency_id', ['text' => 'Валюта', 'renderer' => fn($r, $v) => $r['currency_name']])
            ->text('rate', ['text' => 'Курс', 'renderer' => fn($r, $v) => round($v) . ' ' . $r['currency_code_char']])
            ->text('period', ['text' => 'Период действия'])
            ->enum('status', ['text' => 'Статус', 'enumClass' => StatusEnum::class]);
    }

    protected function formFactory(): FormContract
    {
        return Form::hotel('hotel_id', ['label' => 'Отель', 'emptyItem' => '', 'required' => true])
            ->client('client_id', ['label' => 'Клиент', 'emptyItem' => '', 'required' => true])
            //@todo добавить реализацию получения валют клиента через API
            ->currency('currency_id', ['label' => 'Валюта', 'required' => true])
            ->number('rate', ['label' => 'Курс', 'required' => true])
            //@todo добавить реализацию поля dateRange
            ->dateRange('period', ['label' => 'Период действия', 'required' => true]);
    }

    private function searchForm()
    {
        return (new Search())
            ->currency('country_id', ['label' => __('label.currency'), 'emptyItem' => ''])
            ->dateRange('date_form', ['label' => 'Дата начала'])
            ->dateRange('date_to', ['label' => 'Дата завершения']);
    }
}
