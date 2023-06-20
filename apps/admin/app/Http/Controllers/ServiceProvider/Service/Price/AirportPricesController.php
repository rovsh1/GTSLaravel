<?php

namespace App\Admin\Http\Controllers\ServiceProvider\Service\Price;

use App\Admin\Http\Requests\ServiceProvider\UpdateAirportPriceRequest;
use App\Admin\Models\ServiceProvider\AirportPrice;
use App\Admin\Models\ServiceProvider\AirportService;
use App\Admin\Models\ServiceProvider\Provider;
use App\Admin\Models\ServiceProvider\TransferService;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Layout as LayoutContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AirportPricesController extends AbstractPricesController
{
    public function index(Request $request, Provider $provider): LayoutContract
    {
        $this->provider($provider);

        return Layout::title('Цены')
            ->view('service-provider.service.price.airport.index', [
                'provider' => $provider,
            ]);
    }

    public function getPrices(Request $request, Provider $provider, AirportService $service): JsonResponse
    {
        return response()->json(
            AirportPrice::whereServiceId($service->id)->get()
        );
    }

    public function update(UpdateAirportPriceRequest $request, Provider $provider, AirportService $service): JsonResponse {
        $data = ['currency_id' => $request->getCurrencyId()];
        if ($request->getPriceNet() !== null) {
            $data['price_net'] = $request->getPriceNet();
        }
        if ($request->getPriceGross() !== null) {
            $data['price_gross'] = $request->getPriceGross();
        }

        AirportPrice::updateOrCreate(
            ['service_id' => $service->id, 'car_id' => $request->getCarId(), 'season_id' => $request->getSeasonId()],
            $data
        );

        return response()->json();
    }
}
