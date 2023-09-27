<?php

namespace App\Admin\Http\Controllers\Supplier\Service\Price;

use App\Admin\Http\Requests\ServiceProvider\UpdateTransferPriceRequest;
use App\Admin\Models\Reference\Currency;
use App\Admin\Models\Supplier\CarPrice;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Models\Supplier\TransferService;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Layout as LayoutContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransferPricesController extends AbstractPricesController
{
    public function index(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        return Layout::title('Цены')
            ->view('supplier.service.price.transfer.index', [
                'provider' => $provider,
                'cars' => $provider->cars()->with(['cities'])->get(),
                'seasons' => $provider->seasons,
                'services' => $provider->transferServices,
                'currencies' => Currency::all(),
                'quicksearch' => Grid::enableQuicksearch()->getQuicksearch()
            ]);
    }

    public function getPrices(Request $request, Supplier $provider, TransferService $service): JsonResponse
    {
        return response()->json(
            CarPrice::whereServiceId($service->id)->get()
        );
    }

    public function update(UpdateTransferPriceRequest $request, Supplier $provider, TransferService $service): JsonResponse {
        $data = ['currency_id' => $request->getCurrencyId()];
        if ($request->getPriceNet() !== null) {
            $data['price_net'] = $request->getPriceNet();
        }
        if ($request->getPricesGross() !== null) {
            $data['prices_gross'] = $request->getPricesGross();
        }

        CarPrice::updateOrCreate(
            ['service_id' => $service->id, 'car_id' => $request->getCarId(), 'season_id' => $request->getSeasonId()],
            $data
        );

        return response()->json();
    }
}
