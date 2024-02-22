<?php

namespace App\Admin\Http\Controllers\Supplier\Service\Price;

use App\Admin\Http\Requests\Supplier\UpdateOtherPriceRequest;
use App\Admin\Models\Reference\Currency;
use App\Admin\Models\Supplier\OtherPrice;
use App\Admin\Models\Supplier\Service;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Layout as LayoutContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OtherPricesController extends AbstractPricesController
{
    public function index(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        return Layout::title('Цены')
            ->view('supplier.service.price.other.index', [
                'provider' => $provider,
                'seasons' => $provider->seasons,
                'services' => $provider->otherServices,
                'currencies' => Currency::all(),
                'quicksearch' => Grid::enableQuicksearch()->getQuicksearch()
            ]);
    }

    public function getPrices(Request $request, Supplier $provider, Service $service): JsonResponse
    {
        return response()->json(
            OtherPrice::whereServiceId($service->id)->get()
        );
    }

    public function update(UpdateOtherPriceRequest $request, Supplier $provider, Service $service): JsonResponse
    {
        $data = ['currency' => $request->getCurrency()];
        if ($request->getPriceNet() !== null) {
            $data['price_net'] = $request->getPriceNet();
        }
        if ($request->getPricesGross() !== null) {
            $data['prices_gross'] = $request->getPricesGross();
        }

        OtherPrice::updateOrCreate(
            ['service_id' => $service->id, 'season_id' => $request->getSeasonId()],
            $data
        );

        return response()->json();
    }
}
