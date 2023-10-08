<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Http\Resources\Country as CountryResource;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'country';
    }

    public function search(Request $request): JsonResponse
    {
        $countries = $this->prototype->makeRepository()->query()->get();

        return response()->json(
            CountryResource::collection($countries)
        );
    }

    protected function formFactory(): FormContract
    {
        return Form::localeText('name', ['label' => 'Наименование', 'required' => true])
            //->view('default.form.form')
            ->text('code', ['label' => 'Код страны', 'maxlength' => 2, 'required' => true])
            ->language('language', ['label' => 'Язык по-умолчанию', 'required' => true, 'emptyItem' => ''])
            ->text('phone_code', ['label' => 'Код телефона', 'maxlength' => 8, 'required' => true])
            ->currency('currency', ['label' => 'Валюта', 'required' => true, 'emptyItem' => ''])
            ->checkbox('default', ['label' => 'По умолчанию'])
            ->number('priority', ['label' => 'Приоритет', 'min' => 0, 'max' => 255]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            //->id('id', ['text' => 'ID', 'order' => true])
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('phone_code', ['text' => 'Код телефона'])
            ->text('default', [
                'text' => 'Основная',
                'renderer' => fn($row) => $row->default ? 'Да' : 'Нет'
            ]);
    }
}
