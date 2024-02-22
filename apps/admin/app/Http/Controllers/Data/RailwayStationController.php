<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Http\Requests\RailwayStation\SearchRequest;
use App\Admin\Http\Resources\RailwayStation as Resource;
use App\Admin\Models\Reference\RailwayStation;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Illuminate\Http\JsonResponse;

class RailwayStationController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'railway-station';
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $query = RailwayStation::query();
        if ($request->getCityId() !== null) {
            $query->whereCityId($request->getCityId());
        }

        return response()->json(
            Resource::collection($query->get())
        );
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->city('city_id', ['label' => 'Город', 'emptyItem' => '', 'required' => true])
            ->localeText('name', ['label' => 'Наименование', 'required' => true]);
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
