<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Invoice;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;

class InvoiceController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'invoice';
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
//            ->setSearchForm($this->searchForm())
            ->paginator(self::GRID_LIMIT)
            ->id('id', ['text' => '№', 'route' => $this->prototype->routeName('show')]);
//            ->enum('type', ['text' => 'Тип', 'enum' => TypeEnum::class])
//            ->text('markup_group_name', ['text' => 'Наценка'])
//            ->text('country_name', ['text' => 'Страна'])
//            ->text('city_name', ['text' => 'Город'])
//            ->enum('status', ['text' => 'Статус', 'enum' => StatusEnum::class])
//            ->orderBy('name', 'asc');
    }

    protected function formFactory(): FormContract
    {
        return Form::text('name', ['label' => 'ФИО или название компании', 'required' => true])
//            ->enum('type', ['label' => 'Тип', 'enum' => TypeEnum::class, 'required' => true, 'emptyItem' => ''])
            ->hidden('gender', ['label' => 'Пол'])
            ->city('city_id', ['label' => 'Город', 'required' => true, 'emptyItem' => ''])
//            ->enum('status', ['label' => 'Статус', 'enum' => StatusEnum::class])
            ->currency('currency', ['label' => 'Валюта', 'required' => true, 'emptyItem' => ''])
//            ->enum('residency', ['label' => 'Тип цены', 'enum' => ResidencyEnum::class, 'required' => true, 'emptyItem' => ''])
            ->select('markup_group_id', [
                'label' => 'Группа наценки',
                'required' => true,
                'emptyItem' => '',
//                'items' => MarkupGroup::get()
            ])
            ->manager('administrator_id', ['label' => 'Менеджер', 'emptyItem' => '']);
    }

    private function searchForm()
    {
        return (new SearchForm());
//            ->select('country_id', ['label' => 'Страна', 'emptyItem' => '', 'items' => Country::get()])
//            ->city('city_id', ['label' => 'Город', 'emptyItem' => ''])
//            ->enum('type', ['label' => 'Тип', 'enum' => TypeEnum::class, 'emptyItem' => ''])
//            ->enum('status', ['label' => 'Источник', 'enum' => StatusEnum::class, 'emptyItem' => ''])
//            ->select('markup_group_id', [
//                'label' => 'Группа наценки',
//                'emptyItem' => '',
//                'items' => MarkupGroup::get()
//            ]);
    }
}
