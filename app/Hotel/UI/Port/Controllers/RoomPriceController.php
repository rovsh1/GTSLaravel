<?php

namespace GTS\Hotel\UI\Port\Controllers;

use Carbon\CarbonPeriod;

use Custom\Framework\Port\Request;

use GTS\Hotel\Infrastructure\Facade\RoomPriceFacadeInterface;

class RoomPriceController
{
    public function __construct(
        private RoomPriceFacadeInterface $roomPriceFacade
    ) {}

    public function updateRoomPrice(Request $request)
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'rate_id' => 'required|numeric',
            'price' => 'required|numeric',
            'guests_number' => 'required|int',
            'currency_code' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        return $this->roomPriceFacade->updateRoomPrice(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->rate_id,
            $request->guests_number,
            $request->price,
            $request->currency_code,
        );
    }
}
