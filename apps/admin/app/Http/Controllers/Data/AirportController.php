<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Http\Requests\Airport\SearchAirportRequest;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Illuminate\Http\JsonResponse;

class AirportController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'airport';
    }

    public function search(SearchAirportRequest $request): JsonResponse
    {
        return response()->json([['id' => 1, 'name' => 'Аэропорт Мира']]);
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->city('city_id', ['label' => 'Город', 'emptyItem' => '', 'required' => true])
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->text('code', ['label' => 'Код', 'required' => true]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('city_name', ['text' => 'Город', 'order' => true])
            ->orderBy('name', 'asc');
    }
}
