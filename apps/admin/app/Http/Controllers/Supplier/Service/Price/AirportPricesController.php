<?php

namespace App\Admin\Http\Controllers\Supplier\Service\Price;

use App\Admin\Http\Requests\Supplier\UpdateAirportPriceRequest;
use App\Admin\Models\Reference\Currency;
use App\Admin\Models\Supplier\AirportPrice;
use App\Admin\Models\Supplier\Service;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Layout as LayoutContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AirportPricesController extends AbstractPricesController
{
    public function index(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        return Layout::title('Цены')
            ->view('supplier.service.price.airport.index', [
                'provider' => $provider,
                'airports' => $provider->airports,
                'seasons' => $provider->seasons,
                'services' => $provider->airportServices,
                'currencies' => Currency::all(),
                'quicksearch' => Grid::enableQuicksearch()->getQuicksearch()
            ]);
    }

    public function getPrices(Request $request, Supplier $provider, Service $service): JsonResponse
    {
        return response()->json(
            AirportPrice::whereServiceId($service->id)->get()
        );
    }

    public function update(UpdateAirportPriceRequest $request, Supplier $provider, Service $service): JsonResponse {
        $data = ['currency' => $request->getCurrency()];
        if ($request->getPriceNet() !== null) {
            $data['price_net'] = $request->getPriceNet();
        }
        if ($request->getPricesGross() !== null) {
            $data['prices_gross'] = $request->getPricesGross();
        }

        AirportPrice::updateOrCreate(
            ['service_id' => $service->id, 'season_id' => $request->getSeasonId()],
            $data
        );

        return response()->json();
    }
}
