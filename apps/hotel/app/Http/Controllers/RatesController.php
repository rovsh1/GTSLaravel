<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Http\Requests\PriceRate\SearchRequest;
use App\Hotel\Http\Resources\PriceRate as PriceRateResource;
use App\Hotel\Models\Hotel;
use App\Hotel\Models\PriceRate;
use App\Hotel\Models\Room;
use Illuminate\Http\JsonResponse;

class RatesController extends AbstractHotelController
{
    public function search(SearchRequest $request): JsonResponse
    {
        $query = PriceRate::query()->whereHotelId($this->getHotel()->id);
        if ($request->getRoomId() !== null) {
            $query = Room::whereHotelId($this->getHotel()->id)->whereId($request->getRoomId())->first()->priceRates();
        }

        return response()->json(
            PriceRateResource::collection(
                $query->get()
            )
        );
    }
}
