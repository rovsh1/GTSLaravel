<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Hotel\BatchUpdatePriceRequest;
use App\Admin\Http\Requests\Hotel\UpdatePriceRequest;
use App\Admin\Http\Resources\Price;
use App\Admin\Http\Resources\PriceRate;
use App\Admin\Http\Resources\SeasonPrice;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Season;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Hotel\PricesAdapter;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\View\Menus\HotelMenu;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index(Request $request, Hotel $hotel)
    {
        $this->bootHotel($hotel);

        return Layout::title((string)$hotel)
            ->view('hotel.prices.prices', [
                'hotel' => $hotel,
                'rooms' => $hotel->rooms->load(['priceRates']),
                'seasons' => $hotel->seasons,
                'priceRates' => PriceRate::collection($hotel->priceRates),
            ]);
    }

    public function getSeasonsPrices(Request $request, Hotel $hotel, Season $season): JsonResponse
    {
        $prices = PricesAdapter::getSeasonsPrices($hotel->id);

        return response()->json(
            SeasonPrice::collection($prices)
        );
    }

    public function updateSeasonPrice(UpdatePriceRequest $request, Hotel $hotel, Season $season): AjaxResponseInterface
    {
        PricesAdapter::setSeasonPrice(
            roomId: $request->getRoomId(),
            seasonId: $season->id,
            rateId: $request->getRateId(),
            guestsCount: $request->getGuestsCount(),
            isResident: $request->getIsResident(),
            price: $request->getPrice(),
        );

        return new AjaxSuccessResponse();
    }

    public function getDatePrices(Request $request, Hotel $hotel, Season $season): JsonResponse
    {
        $prices = PricesAdapter::getDatePrices($season->id);

        return response()->json(
            Price::collection($prices)
        );
    }

    public function updateDatePrice(UpdatePriceRequest $request, Hotel $hotel, Season $season): AjaxResponseInterface
    {
        $request->validate([
            'date' => ['required', 'date']
        ]);

        PricesAdapter::setDatePrice(
            roomId: $request->getRoomId(),
            seasonId: $season->id,
            rateId: $request->getRateId(),
            guestsCount: $request->getGuestsCount(),
            isResident: $request->getIsResident(),
            price: $request->getPrice(),
            date: $request->getDate()
        );

        return new AjaxSuccessResponse();
    }

    public function batchUpdateDatePrice(
        BatchUpdatePriceRequest $request,
        Hotel $hotel,
        Season $season
    ): AjaxResponseInterface {
        foreach ($request->getPeriod() as $date) {
            //@todo переделать на норм запрос
            $isNeedUpdate = in_array($date->dayOfWeekIso, $request->getWeekDays());
            if (!$isNeedUpdate) {
                continue;
            }
            PricesAdapter::setDatePrice(
                roomId: $request->getRoomId(),
                seasonId: $season->id,
                rateId: $request->getRateId(),
                guestsCount: $request->getGuestsCount(),
                isResident: $request->getIsResident(),
                price: $request->getPrice(),
                date: $date
            );
        }

        return new AjaxSuccessResponse();
    }

    private function bootHotel($hotel)
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.prices.index', $hotel), 'Цены');

        Sidebar::submenu(new HotelMenu($hotel, 'prices'));
    }
}
