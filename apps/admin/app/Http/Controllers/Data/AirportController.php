<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Http\Requests\Airport\SearchAirportRequest;
use App\Admin\Http\Resources\Airport as AirportResource;
use App\Admin\Models\Reference\Airport;
use App\Admin\Models\Supplier\AirportPrice;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;

class AirportController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'airport';
    }

    public function search(SearchAirportRequest $request): JsonResponse
    {
        $airportsQuery = Airport::query();
        if ($request->getServiceId() !== null) {
            $serviceAirportIds = AirportPrice::whereServiceId($request->getServiceId())
                ->get()
                ->pluck('airport_id')
                ->unique()
                ->toArray();

            $airportsQuery->whereIn('r_airports.id', $serviceAirportIds);
        }

        if ($request->getCityId() !== null) {
            $airportsQuery->whereCityId($request->getCityId());
        }

        $supplierId = $request->getSupplierId();
        if ($supplierId !== null) {
            $airportsQuery->whereExists(function (Builder $query) use ($supplierId) {
                $query->selectRaw(1)
                    ->from('supplier_airports')
                    ->whereColumn('supplier_airports.airport_id', '=', 'r_airports.id')
                    ->where('supplier_airports.supplier_id', $supplierId);
            });
        }

        return response()->json(
            AirportResource::collection($airportsQuery->get())
        );
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
